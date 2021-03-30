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

    if ($(this).attr('type') == "checkbox")
        eles.value = $(this).is(":checked") ? "1" : "0";
    else
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
        else if (response.field == 'actionability_interest')
        {
            if (response.value == "1")
                $('#profile-interest-actionability').show();
            else
                $('#profile-interest-actionability').hide();
        }
        else if (response.field == 'dosage_interest')
        {
            if (response.value == "1")
                $('#profile-interest-dosage').show();
            else
                $('#profile-interest-dosage').hide();
        }
        else if (response.field == 'validity_interest')
        {
            if (response.value == "1")
                $('#profile-interest-validity').show();
            else
                $('#profile-interest-validity').hide();
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