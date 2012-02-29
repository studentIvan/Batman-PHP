$(document).ready(function() {
    /**
     * 1. Get admin content
     * 2. If unauth - init auth form
     * 3. On auth if 403 - display error
     * 4. If auth - get admin content
     */
    $('#greeting').html('');
    $("#auth-form").submit(function() {
        var
            login = $('#login').val(),
            password = $('#password').val();

        if (login.length > 0 && password.length > 0)
        {
            $.ajax({
                url: "/admin/auth",
                type: "POST",
                cache: false,
                data: "login=" + login + "&password=" + password,
                statusCode:
                {
                    403: function(data) {
                        console.error(data);
                    },
                    200: function(data) {
                        console.log(data);
                    }
                }
            });
        }

        return false;
    });
});