<?php include("layouts/app.blade.php"); ?>


        <!-- Begin Hiraola's Breadcrumb Area -->

        <div class="breadcrumb-area">

            <div class="container">

                <div class="breadcrumb-content">

                    <h2>Other</h2>

                    <ul>

                        <li><a href="{{URL::to('/')}}">Home</a></li>

                        <li class="active">Wishlist</li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- Hiraola's Breadcrumb Area End Here -->

        <!--Begin Hiraola's Wishlist Area -->

        <div class="hiraola-wishlist_area">

            <div class="container">

                <div class="row">

                    <div class="col-12">

                        <form action="javascript:void(0)">

                            <div class="table-content table-responsive">

                                <table class="table">

                                    <thead>

                                        <tr>

                                            <th class="hiraola-product_remove">remove</th>

                                            <th class="hiraola-product-thumbnail">images</th>

                                            <th class="cart-product-name">Product</th>

                                            <th class="hiraola-product-price">Unit Price</th>

                                            <th class="hiraola-product-stock-status">Stock Status</th>

                                            <th class="hiraola-cart_btn">add to cart</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                            <td class="hiraola-product_remove"><a href="javascript:void(0)"><i class="fa fa-trash"

                                                title="Remove"></i></a></td>

                                            <td class="hiraola-product-thumbnail"><a href="javascript:void(0)"><img src="assets/images/product/small-size/2-1.jpg" alt="Hiraola's Wishlist Thumbnail"></a>

                                            </td>

                                            <td class="hiraola-product-name"><a href="javascript:void(0)">Juma rema pola</a></td>

                                            <td class="hiraola-product-price"><span class="amount">£23.39</span></td>

                                            <td class="hiraola-product-stock-status"><span class="in-stock">in stock</span></td>

                                            <td class="hiraola-cart_btn"><a href="cart.html">add to cart</a></td>

                                        </tr>

                                        <tr>

                                            <td class="hiraola-product_remove"><a href="javascript:void(0)"><i class="fa fa-trash"

                                                title="Remove"></i></a></td>

                                            <td class="hiraola-product-thumbnail"><a href="javascript:void(0)"><img src="assets/images/product/small-size/2-2.jpg" alt="Hiraola's Wishlist Thumbnail"></a>

                                            </td>

                                            <td class="hiraola-product-name"><a href="javascript:void(0)">Suretin mipen ruma</a></td>

                                            <td class="hiraola-product-price"><span class="amount">£30.50</span></td>

                                            <td class="hiraola-product-stock-status"><span class="in-stock">in stock</span></td>

                                            <td class="hiraola-cart_btn"><a href="cart.html">add to cart</a></td>

                                        </tr>

                                        <tr>

                                            <td class="hiraola-product_remove"><a href="javascript:void(0)"><i class="fa fa-trash"

                                                title="Remove"></i></a></td>

                                            <td class="hiraola-product-thumbnail"><a href="javascript:void(0)"><img src="assets/images/product/small-size/2-3.jpg" alt="Hiraola's Wishlist Thumbnail"></a>

                                            </td>

                                            <td class="hiraola-product-name"><a href="javascript:void(0)">Bag Goodscol model</a></td>

                                            <td class="hiraola-product-price"><span class="amount">£40.19</span></td>

                                            <td class="hiraola-product-stock-status"><span class="out-stock">out stock</span></td>

                                            <td class="hiraola-cart_btn"><a href="cart.html">add to cart</a></td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <!-- Hiraola's Wishlist Area End Here -->
<?php include("layouts/footer.php"); ?>
