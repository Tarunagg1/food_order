jQuery("#register").on('submit',function(e){
    e.preventDefault();
    jQuery('.errmsg').html('');
    jQuery('#registerbtn').attr('disabled',true);
    jQuery('#form_msg').html('Please wait...')
    jQuery.ajax({
        url:'login-register-submit',
        type:"post",
        data:jQuery("#register").serialize(),
        success:function(res){
                jQuery('#form_msg').html('')
                jQuery('#registerbtn').attr('disabled',false);
            let data = jQuery.parseJSON(res);
            if(data.status == 'error'){
                $('#'+data.field).html(data.msg)
            }else{
                $('#'+data.field).html(data.msg)
                jQuery("#register")[0].reset();
            }
        }
    })
})


jQuery("#loginform").on('submit',function(e){
    e.preventDefault();
    jQuery('#loginerr').html('Please wait..');
    jQuery('#loginbtn').attr('disabled',false);
    jQuery.ajax({
        url:'login-register-submit',
        type:"post",
        data:jQuery('#loginform').serialize(),
        success:function(res){
            jQuery('#loginerr').html('')
            jQuery('#loginbtn').attr('disabled',false);
            let data = jQuery.parseJSON(res);
            if(data.status == 'error'){
                $('#'+data.field).html(data.msg)
            }else{
                let ischeckout = jQuery('#ischeckout').val();
                if(ischeckout == 'yes'){
                    window.location.href = 'checkout';                        
                }else{
                    window.location.href = 'shop';
                }
            }
        }
    })
})

jQuery("#forgot-form").on('submit',function(e){
    e.preventDefault();
    jQuery('#forgotpasserr').html('Please wait..');
    jQuery('#forgotbn').attr('disabled',false);
    jQuery.ajax({
        url:'login-register-submit',
        type:"post",
        data:jQuery('#forgot-form').serialize(),
        success:function(res){
            jQuery('#forgotpasserr').html('')
            jQuery('#forgotbn').attr('disabled',false);
            let data = jQuery.parseJSON(res);
            if(data.status == 'error'){
                $('#'+data.field).html(data.msg)
            }
        }
    })
})





function setcheckbox(id) {
        var cat_dish = jQuery("#cat_dish").val();
        var check = cat_dish.search(":"+id);
        if(check != '-1'){
            cat_dish = cat_dish.replace(":"+id,'');
        }else{
            cat_dish=cat_dish+":"+id;
        }
            jQuery("#cat_dish").val(cat_dish);
            jQuery("#frmcatdist")[0].submit();
    }

function setfood(type){
        jQuery("#type").val(type);
        jQuery("#frmcatdist")[0].submit();
}
function addtocart(id,type){
        const qty = jQuery("#qty"+id).val();
        const attr = jQuery('input[name="radio_'+id+'"]:checked').val();
        if(qty <= 0){
            swal("Ohh!", "Please select qty!", "warning")
        }else{
            if(attr != undefined){
                jQuery.ajax({
                    url:"manage_cart",
                    type:"post",
                    data:'qty='+qty+'&attr='+attr+'&type='+type,
                    success:function(res){
                        data = jQuery.parseJSON(res);
                        swal("Good job!", "Dish added to cart!", "success")
                        jQuery("#allreadyadded_"+id).html('(Added-'+qty+')');
                        jQuery("#totaldishcount").html(data.totalcartdish);
                        jQuery("#totalcartprice").html('Rs '+data.totalcartprice)
                        if(data.totalcartdish == 1){
                            var html = `<div id="cartmenu" class="shopping-cart-content">
                                            <ul id="cart_ul">
                                                <li id="attr_${attr}" class="single-shopping-cart">
                                                    <div class="shopping-cart-img">
                                                        <a href="javascript:void(0)"><img style="width: 100%;" alt="" src="http://localhost/foodorder/media/dish/${data.dishimg}"></a>
                                                    </div>
                                                    <div class="shopping-cart-title">
                                                        <h4><a href="javascript:void(0)">${data.dishname} </a></h4>
                                                        <h6>Qty: ${qty}</h6>
                                                        <span>Rs ${data.dishprice*qty}</span>
                                                    </div>
                                                    <div class="shopping-cart-delete">
                                                        <a href="javascript:void(0)" onclick="delete_cart(${attr})"><i class="ion ion-close"></i></a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="shopping-cart-total">
                                                <h4>Total : <span id="cartinsidetotal" class="shop-total">Rs ${data.totalcartprice}</span></h4>
                                            </div>
                                            <div class="shopping-cart-btn">
                                                <a onclick="gotocart()" href="javascript:void(0)">view cart</a>
                                                <a href="checkout.php">checkout</a>
                                            </div>
                                    </div>`;
                            jQuery(".header-cart").append(html);
                        }else{
                             var html = `<li id="attr_${attr}" class="single-shopping-cart">
                                                    <div class="shopping-cart-img">
                                                        <a href="javascript:void(0)"><img style="width: 100%;" alt="" src="http://localhost/foodorder/media/dish/${data.dishimg}"></a>
                                                    </div>
                                                    <div class="shopping-cart-title">
                                                        <h4><a href="#">${data.dishname} </a></h4>
                                                        <h6>Qty: ${qty}</h6>
                                                        <span>Rs ${data.dishprice*qty}</span>
                                                    </div>
                                                    <div class="shopping-cart-delete">
                                                        <a href="javascript:void(0)" onclick="delete_cart(${attr})"><i class="ion ion-close"></i></a>
                                                    </div>
                                        </li>`;
                            jQuery("#cart_ul").append(html);
                            jQuery('#cartinsidetotal').html("Rs "+data.totalcartprice)
                        }
                    }
                 })
            }else{
                swal("Ohh Snap!!", "Attribute is not selected!", "warning")
            }
        }
}



function delete_cart(id,isload='')
{
    jQuery.ajax({
        url:"manage_cart",
        type:"POST",
        data:'attr='+id+'&type='+'delete',
        success:function(res){
            data = jQuery.parseJSON(res);
             jQuery("#allreadyadded_"+id).html('');
            jQuery("#totaldishcount").html(data.totalcartdish);
            jQuery("#totalcartprice").html('Rs '+data.totalcartprice)
            jQuery('#cartinsidetotal').html("Rs "+data.totalcartprice)
            if(data.totalcartdish == 0){
                jQuery("#cartmenu").remove();
            }else{
                jQuery("#attr_"+id).remove();
            }
            if(isload != ''){
                window.location.href = 'cart-page';
            }
            swal("Good job!", "Item Deleted from cart!", "success")
        }
    })
}


function gotocart(){
    window.location.href = "cart-page";
}

function gotocheckout(){
    window.location.href = "checkout";
}



///////////////////// uppdate form
jQuery("#frmProfile").on('submit',function(e){
    e.preventDefault();
    jQuery('#form_msg').html('Please wait..');
    jQuery('#profile_submit').attr('disabled',false);
    jQuery.ajax({
        url:'update_profilesubmit',
        type:"post",
        data:jQuery('#frmProfile').serialize(),
        success:function(res){
            let data = jQuery.parseJSON(res);
            jQuery('#profile_submit').attr('disabled',false);
            jQuery('#form_msg').html('')
            if(data.status == 'success'){
                jQuery('#headeruname').html(jQuery('#uname').val());
                swal("Good job!", data.msg, "success")
            }else{
                swal("Ohh snap!",data.msg , "error")
            }
        }
    })
})

jQuery("#frmPassword").on('submit',function(e){
    e.preventDefault();
    jQuery('#password_form_msg').html('Please wait..');
    jQuery('#password_submit').attr('disabled',false);
    jQuery.ajax({
        url:'update_profilesubmit',
        type:"post",
        data:jQuery('#frmPassword').serialize(),
        success:function(res){
            let data = jQuery.parseJSON(res);
            jQuery('#password_submit').attr('disabled',false);
            jQuery('#form_msg').html('')
            if(data.status == 'success'){
                swal("Good job!", data.msg, "success")
            }
            if(data.status == 'error'){
                swal("Ohh snap!",data.msg , "error")
            }
            console.log(data)
        }
    })
})

