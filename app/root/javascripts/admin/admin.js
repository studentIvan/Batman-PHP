function buildUserInterface()
{
    $.getJSON('/' + adminPath + '/getMap', function(map)
    {
        $("#methods-listener ul").html('');

        $.each(map, function(bundle, solutions) {
            $.each(solutions, function(solution, methods) {
                $("#methods-listener ul").append(
                    '<li class="nav-header">' + bundle + ':' + solution + '</li>');
                $.each(methods, function(methodName, parameters) {
                    $("#methods-listener ul").append(
                        '<li><a href="#" data-link="' + bundle + ':' + solution + ':' +
                            methodName + '" data-parameters="' + parameters.join(':') + '" ' +
                            'onclick="setUpMethodInterface(this)">' + methodName + '</a></li>'
                    );
                })
            })
        });

        $("#methods-listener").fadeIn();
    })
}

function setUpMethodInterface(menuLink)
{
    var pprp = '';
    $.each($(menuLink).data('parameters').split(':'), function(x, param) {
        pprp +=
            '<div class="control-group">' +
                '<label class="control-label" for="p' + param + '">' + param + '</label>' +
                '<div class="controls">' +
                '<input type="text" class="input-xlarge" id="p' + param + '" name="' + param + '">' +
                '</div></div>'
    });

    var needle = $(menuLink).data('link');

    var content = '<h1>' + needle + '</h1><br><hr>' +
        '<form class="form-horizontal"><fieldset>' + pprp +
        '<div class="form-actions">' +
        '<button class="btn btn-primary" type="submit" id="submit-144">Execute</button>' +
        '<div id="submit-145" class="progress progress-info progress-striped ' +
        'active" style="width: 30%; display: none;">' +
        '<div class="bar" style="width: 100%;"></div></div>' +
        '</div></fieldset></form>';

    $("#method-interface").html(content);
    $("#method-interface form").submit(function()
    {
        var postObject = {};
        postObject['needle'] = needle;

        $(this).find(".input-xlarge").each(function() {
            postObject[$(this).attr('name')] = $(this).val();
        });

        $("#submit-144").css('display', 'none');
        $("#submit-145").css('display', 'block');

        $.post('/' + adminPath + '/execute', postObject)

            .complete(function() {
                $("#submit-144").css('display', 'block');
                $("#submit-145").css('display', 'none');
            })

            .success(function()
            {
                $("#method-interface").prepend(
                    '<div class="alert alert-success">' +
                        '<a class="close" data-dismiss="alert">×</a>' +
                        '<strong>Well done!</strong> Executed success.</div>'
                );
            })

            .error(function(wrongInfo)
            {
                wrongInfo = wrongInfo.responseText || 'Method execution failed';
                $("#method-interface").prepend(
                    '<div class="alert alert-error">' +
                        '<a class="close" data-dismiss="alert">×</a>' +
                        '<strong>Fuck!</strong> ' + wrongInfo + '.</div>'
                );
            });

        return false;
    })
}

$(document).ready(function()
{
    adminPath = adminPath || 'admin';

    $("#auth-form").submit(function() {
        var
            login = $('#login').val(),
            password = $('#password').val();

        if (login.length > 0 && password.length > 0)
        {
            $("#auth-form .btn").html('Waiting...');
            $.ajax({
                url: "/" + adminPath + "/auth",
                type: "POST",
                cache: false,
                data: "login=" + login + "&password=" + password,
                statusCode:
                {
                    403: function(data)
                    {
                        $("#auth-form .btn").html('Sign in');
                        $("#xmodal-body").html(data.responseText);
                        $("#myModal").modal();
                    },

                    200: function(data)
                    {
                        $("#auth-form")
                            .after('<p class="navbar-text pull-right">You are logged in, click &quot;exit&quot; for destroy session: <a href="/admin/out">exit</a></p>')
                            .remove();
                        buildUserInterface();
                    }
                }
            });
        }

        return false;
    });

    if (authorized !== 0) {
        buildUserInterface();
    }
})