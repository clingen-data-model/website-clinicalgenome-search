/*
/*
**	Update server when field value changes anywhere in the tab
*/
$('#modalSettings').on('change', '.api-update', function(e) {
    
    //var id = $(this).attr('data-uuid');
    $.ajaxSetup({
        cache: true,
        contentType: "application/x-www-form-urlencoded",
        processData: true,
        headers:{
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN' : window.token,
            'Authorization':'Bearer ' + Cookies.get('laravel_token')
        }
    });
  
    
    var url = "/api/profile";

    var eles = new Object;
    eles._token = window.token;
    //eles.arg = id;
    eles.name = $(this).attr('name');
    eles.value = $(this).val();
    
    var save = $(this).attr('value');
    var savethis = $(this);

    $.post(url, eles, function(response)
    {
        console.log(response.field);
        // update display
        if (response.field == 'credentials')
        {
            $('#profile-credentials').html(response.value);
        }
        else if (response.field == 'name')
        {
            $('#profile-name').html(response.value);
        }

            
    }).fail(function(response)
    {
        savethis.val(savethis.attr('value'));	

        //handle failed validation
        swal({
            title: "Error!",
            text: "An error was encountered communicating with the server",
            icon: "warning",
            button: "OK"
        })
    });
    
});