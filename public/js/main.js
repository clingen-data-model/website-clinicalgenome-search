!function(e){var a={};function t(o){if(a[o])return a[o].exports;var r=a[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=e,t.c=a,t.d=function(e,a,o){t.o(e,a)||Object.defineProperty(e,a,{enumerable:!0,get:o})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,a){if(1&a&&(e=t(e)),8&a)return e;if(4&a&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(t.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&a&&"string"!=typeof e)for(var r in e)t.d(o,r,function(a){return e[a]}.bind(null,r));return o},t.n=function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(a,"a",a),a},t.o=function(e,a){return Object.prototype.hasOwnProperty.call(e,a)},t.p="/",t(t.s=37)}({37:function(e,a,t){e.exports=t(38)},38:function(e,a){$(".action-login-register").on("click",(function(){$("#modalLogin").modal("hide"),$("#register-form")[0].reset(),$("#modalRegister").modal("show")})),$(".action-register-login").on("click",(function(){$("#modalRegister").modal("hide"),$("#login-form")[0].reset(),$("#modalLogin").modal("show")})),$(".action-login-forgot").on("click",(function(){$("#modalLogin").modal("hide"),$("#forgot-form")[0].reset(),$("#modalForgot").modal("show")})),$(".action-forgot-login").on("click",(function(){$("#modalForgot").modal("hide"),$("#login-form")[0].reset(),$("#modalLogin").modal("show")})),$(".action-logout-now").on("click",(function(){$("#logout-form").submit()})),$("#logout-form").validate({submitHandler:function(e){$.ajaxSetup({cache:!0,contentType:"application/x-www-form-urlencoded",processData:!0,headers:{"X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":window.token,Authorization:"Bearer "+Cookies.get("clingen_dash_token")}});var a=$(e).serialize();$.post("/api/logout",a,(function(e){Cookies.remove("clingen_dash_token"),$("#nav-user-name").html("Member"),$("#dashboard-menu").hide(),$("#login-menu").show(),$("#curated-filter-dashboard").trigger("logout"),swal({title:"You have logged out!",text:" ",timer:2500,className:"swal-success",buttons:!1}),window.auth=0,$("#dashboard-logout").trigger("login")})).fail((function(e){alert("Error Logging Out")}))}}),$("#login-form").validate({submitHandler:function(e){$.ajaxSetup({cache:!0,contentType:"application/x-www-form-urlencoded",processData:!0});var a=$(e).serialize();$.post("/api/login",a,(function(e){if(0==e.expires_at?Cookies.set("clingen_dash_token",e.access_token):Cookies.set("clingen_dash_token",e.access_token,{expires:e.expires_at}),$("#modalLogin").modal("hide"),swal({title:"You are now logged in!",text:" ",timer:2500,className:"swal-success",buttons:!1}),$("#nav-user-name").html(e.user),$("#login-menu").hide(),$("#dashboard-menu").show(),e.context){var a=$(".stats-banner").find(".fa-star").css("color");void 0!==a&&"rgb(211, 211, 211)"==a&&$(".stats-banner").find(".fa-star").css("color","green"),$("#follow-gene-id").collapse("hide")}$("#dashboard-logout").trigger("logout"),$("#curated-filter-dashboard").trigger("login"),$("#preferences-menu").trigger("login"),window.auth=1})).fail((function(e){swal({title:"Error",text:e.responseJSON.message,className:"swal-error",dangerMode:!0})}))},rules:{email:{required:!0,email:!0,maxlength:80}},messages:{email:{required:"Please enter your email address",email:"Please enter a valid email address",maxlength:"Section names must be less than 80 characters"}},errorElement:"em",errorClass:"invalid-feedback",errorPlacement:function(e,a){e.addClass("invalid-feedback"),"checkbox"===a.prop("type")?e.insertAfter(a.parent("label")):e.insertAfter(a)},highlight:function(e,a,t){$(e).addClass("is-invalid").removeClass("is-valid")},unhighlight:function(e,a,t){$(e).addClass("is-valid").removeClass("is-invalid")}}),$("#forgot-form").validate({submitHandler:function(e){$.ajaxSetup({cache:!0,contentType:"application/x-www-form-urlencoded",processData:!0});var a=$(e).serialize();$.post("/api/forgot",a,(function(e){Cookies.set("clingen_dash_token",e.access_token),$("#modalForgot").modal("hide"),swal({title:"Password Reset Link Sent!",text:" ",timer:2500,className:"swal-success",buttons:!1})})).fail((function(e){alert("Error sending link")}))},rules:{email:{required:!0,email:!0,maxlength:80}},messages:{email:{required:"Please enter your email address",email:"Please enter a valid email address",maxlength:"Section names must be less than 80 characters"}},errorElement:"em",errorClass:"invalid-feedback",errorPlacement:function(e,a){e.addClass("invalid-feedback"),"checkbox"===a.prop("type")?e.insertAfter(a.parent("label")):e.insertAfter(a)},highlight:function(e,a,t){$(e).addClass("is-invalid").removeClass("is-valid")},unhighlight:function(e,a,t){$(e).addClass("is-valid").removeClass("is-invalid")}}),$("#register-form").validate({submitHandler:function(e){$.ajaxSetup({cache:!0,contentType:"application/x-www-form-urlencoded",processData:!0});var a=$(e).serialize();$.post("/api/register",a,(function(e){if(Cookies.set("clingen_dash_token",e.access_token),$("#modalRegister").modal("hide"),swal({title:"You have successfully registered!",text:"An confirmation email has been sent to your email address.  Please follow the directions to verify and complete the registration.",className:"swal-success"}),e.context){var a=$(".stats-banner").find(".fa-star").css("color");void 0!==a&&"rgb(211, 211, 211)"==a&&$(".stats-banner").find(".fa-star").css("color","green"),$("#follow-gene-id").collapse("hide")}})).fail((function(e){var a=e.responseJSON.errors;a.hasOwnProperty("email")?swal({title:"Error",text:"Email address is not available",className:"swal-error",dangerMode:!0}):a.hasOwnProperty("password")&&swal({title:"Error",text:a.password[0],className:"swal-error",dangerMode:!0})}))},rules:{email:{required:!0,email:!0,maxlength:80}},messages:{email:{required:"Please enter your email address",email:"Please enter a valid email address",maxlength:"Section names must be less than 80 characters"}},errorElement:"em",errorClass:"invalid-feedback",errorPlacement:function(e,a){e.addClass("invalid-feedback"),"checkbox"===a.prop("type")?e.insertAfter(a.parent("label")):e.insertAfter(a)},highlight:function(e,a,t){$(e).addClass("is-invalid").removeClass("is-valid")},unhighlight:function(e,a,t){$(e).addClass("is-valid").removeClass("is-invalid")}})}});