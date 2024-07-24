import React, { Component } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            products: [],
            customers: [],
            barcode: "",
            search: "",
            customer_id: ""
        };

        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this)
    }

    componentDidMount() {
        // load user cart
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then(res => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then(res => {
            const products = res.data.data;
            this.setState({ products });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode);
        this.setState({ barcode });
    }

    loadCart() {
        axios.get("/admin/cart").then(res => {
            const cart = res.data;
            this.setState({ cart });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/cart", { barcode })
                .then(res => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        const cart = this.state.cart.map(c => {
            if (c.id === product_id) {
                c.pivot.quantity = qty;
            }
            return c;
        });

        this.setState({ cart });

        axios
            .post("/admin/cart/change-qty", { product_id, quantity: qty })
            .then(res => { })
            .catch(err => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    getTotal(cart) {
        const total = cart.map(c => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }
    handleClickDelete(product_id) {
        axios
            .post("/admin/cart/delete", { product_id, _method: "DELETE" })
            .then(res => {
                const cart = this.state.cart.filter(c => c.id !== product_id);
                this.setState({ cart });
            });
    }
    handleEmptyCart() {
        axios.post("/admin/cart/empty", { _method: "DELETE" }).then(res => {
            this.setState({ cart: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
    }
    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode) {
        let product = this.state.products.find(p => p.barcode === barcode);
        if (!!product) {
            // if product is already in cart
            let cart = this.state.cart.find(c => c.id === product.id);
            if (!!cart) {
                // update quantity
                this.setState({
                    cart: this.state.cart.map(c => {
                        if (c.id === product.id && product.quantity > c.pivot.quantity) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    })
                });
            } else {
                if (product.quantity > 0) {
                    product = {
                        ...product,
                        pivot: {
                            quantity: 1,
                            product_id: product.id,
                            user_id: 1
                        }
                    };

                    this.setState({ cart: [...this.state.cart, product] });
                }
            }

            axios
                .post("/admin/cart", { barcode })
                .then(res => {
                    // this.loadCart();
                    console.log(res);
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setCustomerId(event) {
        this.setState({ customer_id: event.target.value });
    }
    handleClickSubmit() {
        Swal.fire({
            title: 'Enter Discount and Received Amount',
            html: `
                <div class="row mb-3 border-bottom mt-3 text-left">
                <div class="col-md-6">
                <p class="m-0"><strong>Total Amount:</strong></p>
                </div>
                <div class="col-md-6">
                <p class="m-0" style="font-size: 24px; font-weight: 800;"> ${this.getTotal(this.state.cart)}</p>
                </div>
                </div>
                <div class="row mb-3 border-bottom mt-3 text-left">
                <div class="col-md-6">
                <p class="m-0">Discount</p>
                </div>
                <div class="col-md-6">
                <input id="swal-input2" class="swal2-input" value="0.00" placeholder="Discount" type="number" step="0.01" name="discount">
                </div>
                </div>
                <div class="row mb-3 border-bottom mt-3 text-left">
                <div class="col-md-6">
                <p class="m-0"><strong>Total Payable:</strong><p>
                </div>
                <div class="col-md-6">
                <p class="m-0" id="total-payable" style="font-size: 24px; font-weight: 800;">${this.getTotal(this.state.cart)}</p>
                </div>
                </div>
                <div class="row mb-3 border-bottom mt-3 text-left">
                <div class="col-md-6">
                <p class="m-0">Received Amount</p>
                </div>
                <div class="col-md-6">
                <input id="swal-input3" class="swal2-input" value="${this.getTotal(this.state.cart)}" placeholder="Received Amount" type="number" step="0.01" name="amount">
                </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Send',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const discount = parseFloat(document.getElementById('swal-input2').value) || 0;
                const total = parseFloat(this.getTotal(this.state.cart)) || 0;
                const amount = parseFloat(document.getElementById('swal-input3').value) || 0;
                const totalPayable = total - discount;

                if (discount > total) {
                    Swal.showValidationMessage('Discount cannot be greater than total amount.');
                    return false;
                }
    
                if (totalPayable <= 0) {
                    Swal.showValidationMessage('Total payable amount cannot be zero or negative.');
                    return false;
                }
    
                return axios.post('/admin/orders', {
                    customer_id: this.state.customer_id,
                    discount: discount,
                    amount: amount
                }).then(res => {
                    this.loadCart();
                    return res.data;
                }).catch(err => {
                    Swal.showValidationMessage(err.response.data.message);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                var order = result.value;
                fetch("/admin/print-order/" + order)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Handle the response data
                        console.log('Request successful', blob);
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                    });
            }
        });
    
        // Update total payable dynamically when discount changes
        const discountInput = document.getElementById('swal-input2');
        discountInput.addEventListener('input', () => {
            const discount = parseFloat(discountInput.value) || 0;
            const total = parseFloat(this.getTotal(this.state.cart)) || 0;
            let totalPayable = total - discount;
    
            if (totalPayable < 0) {
                totalPayable = 0;
            }
    
            document.getElementById('total-payable').innerHTML = `${totalPayable.toFixed(2)}`;
            document.getElementById('swal-input3').value = totalPayable.toFixed(2);
        });
    }
    
    
    
    render() {
        const { cart, products, customers, barcode } = this.state;
        return (
            <div className="row">
                <div className="col-md-6 col-lg-4">
                    <div className="row mb-2">
                        <div className="col">
                            <form onSubmit={this.handleScanBarcode}>
                                <input
                                    type="text"
                                    className="form-control"
                                    placeholder="Scan Barcode..."
                                    value={barcode}
                                    onChange={this.handleOnChangeBarcode}
                                />
                            </form>
                        </div>
                        <div className="col">
                            <select
                                className="form-control"
                                onChange={this.setCustomerId}
                            >
                                <option value="">Walking Customer</option>
                                {customers.map(cus => (
                                    <option
                                        key={cus.id}
                                        value={cus.id}
                                    >{`${cus.first_name} ${cus.last_name}`}</option>
                                ))}
                            </select>
                        </div>
                    </div>
                    <div className="user-cart">
                        <div className="card">
                            <table className="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th className="text-right">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {cart.map(c => (
                                        <tr key={c.id}>
                                            <td>{c.name}</td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className="form-control form-control-sm qty"
                                                    value={c.pivot.quantity}
                                                    onChange={event =>
                                                        this.handleChangeQty(
                                                            c.id,
                                                            event.target.value
                                                        )
                                                    }
                                                />
                                                <button
                                                    className="btn btn-danger btn-sm"
                                                    onClick={() =>
                                                        this.handleClickDelete(
                                                            c.id
                                                        )
                                                    }
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </td>
                                            <td className="text-right">
                                                {window.APP.currency_symbol}{" "}
                                                {(
                                                    c.price * c.pivot.quantity
                                                ).toFixed(2)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col">Total:</div>
                        <div className="col text-right">
                            {window.APP.currency_symbol} {this.getTotal(cart)}
                        </div>
                    </div>
                    <div className="row">
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-danger btn-block"
                                onClick={this.handleEmptyCart}
                                disabled={!cart.length}
                            >
                                Cancel
                            </button>
                        </div>
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-primary btn-block"
                                disabled={!cart.length}
                                onClick={this.handleClickSubmit}
                            >
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
                <div className="col-md-6 col-lg-8">
                    <div className="mb-2">
                        <input
                            type="text"
                            className="form-control"
                            placeholder="Search Product..."
                            onChange={this.handleChangeSearch}
                            onKeyDown={this.handleSeach}
                        />
                    </div>
                    <div className="order-product">
                        {products.map(p => (
                            <div
                                onClick={() => this.addProductToCart(p.barcode)}
                                key={p.id}
                                className="item"
                            >
                                <img src={p.image_url} class="rounded mx-auto d-block" alt="" />
                                <h5 style={window.APP.warning_quantity > p.quantity ? { color: 'red' } : {}}>{p.name}({p.quantity})</h5>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render(<Cart />, document.getElementById("cart"));
}
