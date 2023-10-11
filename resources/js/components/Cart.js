import axios from "axios"
import React, {Component} from "react"
import {QRCodeSVG} from 'qrcode.react';
import ReactDOM  from "react-dom"
import Swal from 'sweetalert2'
import {result, sum} from 'lodash'


class Cart extends Component{

    constructor(props){
        super(props)
        this.state = {
            cart: [],
            products: [],
            barcode: '',
            search: '',
            id:'',
            referenceID: '',
        }
        this.loadCart = this.loadCart.bind(this);
        this.handleManager = this.handleManager.bind(this);
        this.loadProducts = this.loadProducts.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
        this.getTotal = this.getTotal.bind(this);
        this.handleGcashCheckout = this.handleGcashCheckout.bind(this);
        this.handleGcashSubmit = this.handleGcashSubmit.bind(this);
    }

    componentDidMount() {
        this.loadCart();
        this.loadProducts();
    }
    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode)
        this.setState({barcode})
    }
    handleSearch(event){
        if (event.keyCode === 13){
            this.loadProducts(event.target.value)   
        }

    }
    addProductToCart(barcode){
        axios.post('/cart', {barcode}).then(res => {
            this.loadCart();
            this.setState({barcode: ''})
        }).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: err.response.data.message,
              })
        })
    }
    handleScanBarcode(event) {
        event.preventDefault();
        const {barcode} = this.state;
        if(!!barcode){
            axios.post('/cart', {barcode}).then(res => {
                this.loadCart();
                this.setState({barcode: ''})
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err.response.data.message,
                  })
            })
        }

    }
    loadProducts(search = ''){
        const query = !!search ? `?search=${search}` : '';
        axios.get(`/products${query}`).then(res => {
            const products = res.data.data;
            this.setState({products});
        })
    }
    handleClickDelete(product_id){
        axios.all([
            axios.post('/cart/delete', {product_id, _method: 'DELETE'}),
            axios.post('/logs', {
                activity: "has deleted an item"})
        ])
        .then(res => {
            const cart = this.state.cart.filter(c => c.id != product_id);
            this.setState({cart});
        })
    }
    handleEmptyCart(){
        axios.all([
            axios.post('/cart/empty', {_method: 'DELETE'}),
            axios.post('/logs', {
                activity: "has emptied the cart"})
        ]).then(res => {
        this.setState({cart: []});
        })
    }

    handleChangeQty(product_id, qty){
        // add quantity when scanned multiple // change quantity
        const cart = this.state.cart.map(c => {
            if(c.id == product_id){
                c.pivot.quantity = qty;
            }
            return c;
        });
        this.setState({cart});
    }
    handleChangeSearch(event){
        const search = event.target.value;
        this.setState({search})
    }
    handleOnBlur(product_id, qty)
    {
        axios.all([
            axios.post('cart/change-qty', {product_id, quantity: qty}),
            axios.post('/logs', {
                activity: "has manually changed product"})
        ]).then(res => {

        }).catch(err => {
            
            Swal.fire({
                icon: 'error',
                title: 'empty',
                text: err.response.data.message,
              })
              axios.post('/cart/delete', {product_id, _method: 'DELETE'}).then(res => {
                const cart = this.state.cart.filter(c => c.id != product_id);
                this.setState({cart})
            })
        }); 
    }

    getTotal(cart){
        const total = cart.map(c => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }
    loadCart() {
        axios.get('/cart').then(res => {
            const cart = res.data;
            this.setState({cart})
        })
    }
    handleGcashCheckout(){
        const axios = require("axios").default;
        const amount = this.getTotal(this.state.cart)
        const tAmount = parseFloat(this.getTotal(this.state.cart));
        const total = this.getTotal(this.state.cart) * 100
            const options = {
            method: 'POST',
            url: 'https://api.paymongo.com/v1/sources',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                Authorization: 'Basic cGtfdGVzdF84bUpuUnBTcHZpcnI5OXY4UXV2dWZWV3Y6'
            },
            data: {
                data: {
                attributes: {
                    amount: total,
                    redirect: {
                    success: 'https://www.re-leased.com/hubfs/Customer%20Success.jpg',
                    failed: 'https://t3.ftcdn.net/jpg/00/94/63/10/360_F_94631040_PfVaDxuaGPgFyWtMQPsGmYXTGqVo0vbG.jpg'
                    },
                    type: 'gcash',
                    currency: 'PHP'
                }
                }
            }
            };
            axios.request(options).then(function (response) {
                const referenceID = JSON.stringify(response.data.data.id).replace(/['"]+/g, '')
                const redirect = JSON.stringify(response.data.data.attributes.redirect.checkout_url).replace(/['"]+/g, '')
            Swal.fire({
                imageUrl: 'https://api.qrserver.com/v1/create-qr-code/?data=' + redirect + '&amp;size=150x150',
                title: 'Refererence ID:',
                text: '' + referenceID,
                confirmButtonText: `Confirm Payment`,  
                footer: 'QRCode URL: \n' +redirect,
                allowOutsideClick: false,
            }).then((result) => { 
                if (result.isConfirmed) {    
                    const options = {
                        
                        method: 'POST',
                        url: 'https://api.paymongo.com/v1/payments',
                        headers: {
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                            Authorization: 'Basic c2tfdGVzdF9uTmV1SHJuNTlDSjljS1QyUGVVUktjTUU6'
                        },
                        data: {
                            data: {
                            attributes: {
                                amount: total,
                                source: {id: referenceID, type: 'source'},
                                currency: 'PHP'
                            }
                            }
                        }
                        };
                
                        axios.request(options).then(function (response) {
                            axios.post('/orders', {amount, tAmount} ).then(res => {
                                window.location.reload(false);
                        });
                        }).catch(function (error) {
                            const errory = JSON.stringify(error.response.data.errors[0].detail).replace(/['"]+/g, '');
                            console.log(errory)
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errory
                              })
                        
                        });
                    navigator.clipboard.writeText(referenceID) 
                }
            });

            }).catch(function (error) {
                const errorify = JSON.stringify(error.response.data.errors[0].detail).replace(/['"]+/g, '');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorify
                  })
                  console.log()
            });
        }
    handleGcashSubmit(){
        const axios = require("axios").default;
        const amount = this.getTotal(this.state.cart)
        const tAmount = parseFloat(this.getTotal(this.state.cart));
        const total = this.getTotal(this.state.cart) * 100;
        Swal.fire({
            title: 'Reference ID: ',
            input: 'text',
            confirmButtonText: 'Pass',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: (referenceID) => {
                const options = {
                    method: 'POST',
                    url: 'https://api.paymongo.com/v1/payments',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        Authorization: 'Basic c2tfdGVzdF9uTmV1SHJuNTlDSjljS1QyUGVVUktjTUU6'
                    },
                    data: {
                        data: {
                        attributes: {
                            amount: total,
                            source: {id: referenceID, type: 'source'},
                            currency: 'PHP'
                        }
                        }
                    }
                    };
            
                    axios.request(options).then(function (response) {
                        axios.post('/orders', {amount, tAmount} ).then(res => {
                            window.location.reload(false);
                    });
                    }).catch(function (error) {
                        const errorify = JSON.stringify(error.response.data.errors[0].detail).replace(/['"]+/g, '');
                        console.log(errorify)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorify
                          })
                    
                    });
            },
            
        })
        
    }
    handleClickSubmit(){
        Swal.fire({
            title: 'Received Amount',
            input: 'number',
            inputValue: parseFloat(this.getTotal(this.state.cart)),
            inputAttributes: {
                input: 'number',
                required: 'true',
                min: parseFloat(this.getTotal(this.state.cart))
            },
            showCancelButton: true,
            confirmButtonText: 'Pay',
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                const tAmount = parseFloat(this.getTotal(this.state.cart));
                axios.post('/orders', {amount, tAmount} ).then(res => {
                    this.loadCart();
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                var changeAmount = result.value - this.getTotal(this.state.cart);
                if (parseFloat(result.value) > parseFloat(this.getTotal(this.state.cart))){
                    Swal.fire({
                        title: 'Change',
                        text: `₱${changeAmount}`
                        })
                }
        })
    }
    handleManager(product_id){
        Swal.fire({
            title: 'Void Order',
            html: `<input type="text" id="login" class="swal2-input" placeholder="Username">
            <input type="password" id="password" class="swal2-input" placeholder="Password">`,
            confirmButtonText: 'Sign in',
            focusConfirm: false,
            preConfirm: () => {
              const login = Swal.getPopup().querySelector('#login').value
              const pass = Swal.getPopup().querySelector('#password').value
              if (!login || !password) {
                Swal.showValidationMessage(`Please enter login and password`)
              }else{
                axios.post('/cart/void', {login, pass} ).then(res => {
                    if(res.data == 'success'){
                        axios.all([
                            axios.post('/cart/delete', {product_id, _method: 'DELETE'}),
                            axios.post('/logs', {
                                activity: "has deleted an item"})
                        ])
                        .then(res => {
                            const cart = this.state.cart.filter(c => c.id != product_id);
                            this.setState({cart});
                        })
                    }else{
                        Swal.fire('Wrong credentials or has no Manager role')
                    }
                        //Swal.showValidationMessage(`Invalid login or password`)
                    //}
                });
              }
            }
          }).then((result) => {

          })
    }
    
    render(){
        const {cart, products, barcode} = this.state;
        return (
            
            <div>
                 <div className="row">
            <div className="col-md-6 col-lg-4">
                <div className="row">
                    <div className="col">
                        <form onSubmit={this.handleScanBarcode}>
                        <input 
                        type="text" autoFocus
                        className="form-control" 
                        placeholder="Click to scan barcode." 
                        value={barcode}
                        onChange={this.handleOnChangeBarcode}
                        ref={this.barcodeInputRef} />
                        </form>
                    </div>
                </div>
                <div className="user-cart">
                    <div className="card" style={{ height: "400px", overflowY: "scroll" }} >
                        <table className="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th className="text-right">Price</th>
                                </tr>
                            </thead>

                            <tbody>

                                {cart.map(c => (
                                    <tr key={c.id}>
                                    <td>{c.name}</td>
                                    <td> <input 
                                        type="text" 
                                        className="form-control form-control-sm" 
                                        value={c.pivot.quantity} 
                                        onBlur={event => this.handleOnBlur(c.id, event.target.value)}
                                        onChange={event => this.handleChangeQty(c.id, event.target.value)}
                                        style={{width: '45px', float: 'left'}} /> 
                                            <button 
                                            className="btn-danger btn-sm"
                                                onClick={() =>this.handleManager(c.id)}
                                                >
                                                <i className="mdi mdi-delete-outline"></i>
                                            </button>
                                    </td>
                                    <td className="text-right">₱ {(c.price * c.pivot.quantity).toFixed(2)}</td>
                                </tr>
                                ))}

                            </tbody>
                        </table>
                    </div>
            <div className="row" style={{ width: "300px"}}>
            <div className="col" style={{ color: 'black', fontSize: "20px" }}>Total: </div>
            <div className="text-right" style={{ color: 'red', fontSize: "40px" }}>₱ {this.getTotal(cart)}</div>
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
                        Empty Cart
                    </button>
                </div>
                <div 
                    className="col">
                    <button className="btn btn-primary btn-block"
                        disabled={!cart.length}
                        onClick={this.handleClickSubmit}
                    >
                        Submit
                    </button>
                </div>
            </div>
            <button className="btn btn-primary btn-block"
                    disabled={!cart.length}
                    onClick={this.handleGcashCheckout}
                    >Generate Gcash Checkout URL
                    </button>
            <button className="btn btn-primary btn-block"
                    disabled={!cart.length}
                    onClick={this.handleGcashSubmit}
                    >Submit GCASH Payment
                    </button>
            </div>
            <div className="col-md-6 col-lg-8">
            <div>
                <input 
                type="text" 
                className="form-control" 
                placeholder="Search for products" 
                onChange={this.handleChangeSearch}
                onKeyDown={this.handleSearch}/>
            </div>
            <div
            className="order-product" 
            style={{ maxWidth: 20 }}
            >

            </div>
            {products.map(p => (
                <div 
                title= {"Barcode: " + p.barcode + "\n₱" + p.price}
                onClick={() => this.addProductToCart(p.barcode)}
                key={p.id} 
                className="item" 
                style={{float: "left", border: "1px solid #ccc", borderRadius: "3px", background: "#ADD8E6", 
                margin: "0 2px 4px 2px"}}>

                    <img 
                    src={p.image} 
                    alt="Barcode: {p.barcode}"
                    style={{width: "175px", height: "175px", maxWidth: "100%"}} 
                    />

                    <h6 
                    style={{textAlign: "center", fontSize: "15px", wordBreak: "break-all", width: "175px", 
                    color: "black"}}>
                        {p.name}
                        </h6>
                </div>
            ))}
                
                
            

        </div>
        </div>
        </div>
    
        );
    }
}
export default Cart;
if(document.getElementById('cart')){
    ReactDOM.render(<Cart />,document.getElementById('cart'))
}