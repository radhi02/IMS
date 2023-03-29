<script type="text/javascript">

$(document).ready(function() {
    $('#user_country,#user_state,#user_city,#Per_Country,#Per_state,#Per_city').select2();
    $('#customer_country,#customer_state,#customer_city').select2();
    $('#vendor_country,#vendor_state,#vendor_city').select2();
    $('#edit_company_country,#edit_company_state,#edit_company_city').select2();

    var UsrState = <?php if(isset($edit_users->State)) echo $edit_users->State; else echo 'null' ?>;
    var UsrCity = <?php if(isset($edit_users->city)) echo $edit_users->city; else echo 'null' ?>;
    var UsrPerState = <?php if(isset($edit_users->Per_State)) echo $edit_users->Per_State; else echo 'null' ?>;
    var UsrPerCity = <?php if(isset($edit_users->Per_city)) echo $edit_users->Per_city; else echo 'null' ?>;
    var CusState = <?php if(isset($customer->state)) echo $customer->state; else echo 'null' ?>;
    var CusCity = <?php if(isset($customer->city)) echo $customer->city; else echo 'null' ?>;
    var VenState = <?php if(isset($edit_vendor->state)) echo $edit_vendor->state; else echo 'null' ?>;
    var VenCity = <?php if(isset($edit_vendor->city)) echo $edit_vendor->city; else echo 'null' ?>;
    var CompanyState = <?php if(isset($edit_company->state)) echo $edit_company->state; else echo 'null' ?>;
    var CompanyCity = <?php if(isset($edit_company->city)) echo $edit_company->city; else echo 'null' ?>;

    function setState(state,type,userstate = null,UsrPerState = null){
        var country = state;
        var settype = type;
        $("#" + settype).html('');
        $.ajax({
            url: "{{route('all.get_state')}}",
            type: "post",
            data: {
                country: country,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $("#" + settype).html('<option value="">Select State</option>');
                $.each(result, function(key, value) {
                    $("#" + settype).append('<option value="' + value
                        .id + '" data-value="' + value
                        .id + '">' + value.text + '</option>');
                    });
            },
            complete: function(result) {
                if(UsrState != null) { $("#" + settype).select2().val(UsrState).trigger('change'); }
                if(UsrPerState != null) { $("#" + settype).select2().val(UsrPerState).trigger('change'); }
            }
        });
    };

    function setCity(state,type,UsrCity = null,UsrPerCity = null){
        var state = state;
        var settype = type;
        $.ajax({
            url: "{{route('all.get_city')}}",
            type: "post",
            data: {
                state: state,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $("#" + settype).html('<option value="">Select City</option>');
                $.each(result, function(key, value) {
                    $("#" + settype).append('<option value="' + value
                    .id + '" data-value="' + value
                    .id + '">' + value.text + '</option>');
                });
            },
            complete: function(result) {
                if(UsrCity != null) {  $("#" + settype).val(UsrCity).trigger('change'); }
                if(UsrPerCity != null) {  $("#" + settype).val(UsrPerCity).trigger('change'); }
            }
        });
    }
    
    $('#user_country').on('change', function() {
        setState(this.value,'user_state',UsrState,null);
    });

    $('#user_state').on('change', function() {
        setCity(this.value,'user_city',UsrCity,null);
    });
    
    $('#Per_Country').on('change', function() {         
        setState(this.value,'Per_State',null,UsrPerState);
    });

    $('#Per_State').on('change', function() {
        setCity(this.value,'Per_city',null,UsrPerCity);
    });
    
    $('#customer_country').on('change', function() {         
        setState(this.value,'customer_state',null,CusState);
    });

    $('#customer_state').on('change', function() {
        setCity(this.value,'customer_city',null,CusCity);
    });
    
    $('#vendor_country').on('change', function() {         
        setState(this.value,'vendor_state',null,VenState);
    });

    $('#vendor_state').on('change', function() {
        setCity(this.value,'vendor_city',null,VenCity);
    });

    $('#edit_company_country').on('change', function() {         
        setState(this.value,'edit_company_state',null,CompanyState);
    });

    $('#edit_company_state').on('change', function() {
        setCity(this.value,'edit_company_city',null,CompanyCity);
    });

    if(UsrState!=null) { setState($("#user_country").val(),'user_state',UsrState,null); }
    if(UsrCity!=null) { setCity($("#user_state").val(),'user_city',UsrCity,null); }
    if(UsrPerState!=null) { setState($("#Per_Country").val(),'Per_State',null,UsrPerState); }
    if(UsrPerCity!=null) { setCity($("#Per_State").val(),'Per_city',null,UsrPerCity); }
    if(CusState!=null) { setState($("#customer_country").val(),'customer_state',null,CusState); }
    if(CusCity!=null) { setCity($("#customer_state").val(),'customer_city',null,CusCity); }
    if(VenState!=null) { setState($("#vendor_country").val(),'vendor_state',null,VenState); }
    if(VenCity!=null) { setCity($("#vendor_state").val(),'vendor_city',null,VenCity); }
    if(CompanyState!=null) { setState($("#edit_company_country").val(),'edit_company_state',null,CompanyState); }
    if(CompanyCity!=null) { setCity($("#edit_company_state").val(),'edit_company_city',null,CompanyCity); }

});
</script>
