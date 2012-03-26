function showSqlMaster()
{
    $("#method-interface").html('<h1>SQL master</h1>\n<br>\n<hr>\n<div id="json-result" style="display: none"></div>\n<form class="form-horizontal">\n    <fieldset>\n        <div class="control-group">\n            <label class="control-label" for="p_sql">SQL</label>\n            <div class="controls">\n                <textarea id="p_sql" class="input-xlarge" name="_sql" cols="3" rows="3"></textarea>\n            </div>\n        </div>\n        <div class="control-group">\n            <label class="control-label" for="p_dbcfg">connection</label>\n            <div class="controls">\n                <input id="p_dbcfg" class="input-xlarge" name="_dbcfg" value="database">\n            </div>\n        </div>\n        <div class="form-actions">\n            <button id="submit-144" class="btn btn-primary" type="submit">\n                <i class="icon-cog icon-white"></i>\n                Execute\n            </button>\n            <div id="submit-145" class="progress progress-info progress-striped active" style="width: 30%; display: none;">\n                <div class="bar" style="width: 100%;"></div>\n            </div>\n        </div>\n    </fieldset>\n</form>');
    $("#method-interface form").submit(function()
    {
        var postObject = {};
        postObject['needle'] = 'SQL';

        $(this).find(".input-xlarge").each(function() {
            postObject[$(this).attr('name')] = $(this).val();
        });

        $("#submit-144").css('display', 'none');
        $("#submit-145").css('display', 'block');

        $.post('/' + adminPath + '/execute/' + Math.floor(Math.random()*1000), postObject)

            .complete(function() {
                $("#submit-144").css('display', 'block');
                $("#submit-145").css('display', 'none');
            })

            .success(function (successInfo) {
                if (successInfo !== 'ok') {
                    var compiledInfo, key, column;
                    if (typeof successInfo !== 'string') {
                        compiledInfo = '<table id="sortered" ' +
                            'class="table table-striped table-bordered table-condensed">';

                        if (typeof successInfo[0] == 'object') {
                            compiledInfo += '<thead><tr>';
                            for (key in successInfo[0]) {
                                compiledInfo += '<th>' + key + '</th>';
                            }
                            compiledInfo += '</tr></thead>';
                        }

                        compiledInfo += '<tbody>';

                        for (key in successInfo) {
                            if (typeof successInfo[key] == 'object') {
                                compiledInfo += '<tr>';
                                for (column in successInfo[key]) {
                                    compiledInfo += '<td>' + successInfo[key][column] + '</td>';
                                }
                                compiledInfo += '</tr>';
                            }
                            else {
                                compiledInfo += '<tr><td>' + successInfo[key] + '</td></tr>';
                            }
                        }

                        compiledInfo += '</tbody></table>';
                    }
                    else {
                        compiledInfo = successInfo;
                    }

                    $("#json-result").html(compiledInfo).fadeIn();
                    $("#sortered").tablesorter();
                }
                else {
                    $("#method-interface").prepend(
                        '<div class="alert alert-success">' +
                            '<a class="close" data-dismiss="alert">×</a>' +
                            '<strong>Well done!</strong> Executed success.</div>'
                    );
                }
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

function buildUserInterface()
{
    $.getJSON('/' + adminPath + '/getMap', function(map)
    {
        $("#methods-listener ul").html('');

        $.each(map, function(bundle, solutions)
        {
            $.each(solutions, function(solution, methods)
            {
                $("#methods-listener ul").append('<li class="nav-header">' + bundle + ':' + solution + '</li>');

                $.each(methods, function (methodName, rdoc)
                {
                    var displayName = (typeof rdoc['desc'] !== 'undefined'
                        && rdoc['desc'].length > 0) ? rdoc['desc'] : methodName;

                    $("#methods-listener ul").append(
                        '<li><a href="#" data-desc="' + displayName + '" data-link="' + bundle +
                            ':' + solution + ':' + methodName + '" data-parameters="' +
                            rdoc['p'].join(':') + '" data-text="' + rdoc['textareas'].join(':') +
                            '" ' + 'onclick="setUpMethodInterface(this)">' + displayName + '</a></li>'
                    );
                })
            })
        });

        $("#methods-listener").fadeIn();
    })
}

function setUpMethodInterface(menuLink)
{
    var pprp = '', parameters = $(menuLink).data('parameters'),
        dName = $(menuLink).data('desc'), textareas = $(menuLink).data('text').split(':');

    if (parameters) $.each(parameters.split(':'), function(x, param)
    {
        var needed = (param.indexOf('_') !== 0);

        pprp +=
            '<div class="control-group">' +
                '<label class="control-label" for="p' + param + '">' + param.replace(/_(\S+)/, '$1') +
                ((needed) ? '<sup>*</sup>' : '') + '</label><div class="controls">' +
                (($.inArray(param, textareas) !== -1) ?
                '<textarea class="input-xlarge" id="p' + param + '" name="' + param + '" cols="3" rows="3"></textarea>' :
                '<input type="text" class="input-xlarge" id="p' + param + '" name="' + param + '">') +
                '</div></div>'
    });

    var needle = $(menuLink).data('link');

    var content = '<h1>' + dName + '</h1><br><hr>' +
        '<div id="json-result" style="display: none"></div>' +
        '<form class="form-horizontal"><fieldset>' + pprp +
        '<div class="form-actions">' +
        '<button class="btn btn-primary" type="submit" id="submit-144">' +
        '<i class="icon-cog icon-white"></i> Execute</button>' +
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

        $.post('/' + adminPath + '/execute/' + Math.floor(Math.random()*1000), postObject)

            .complete(function() {
                $("#submit-144").css('display', 'block');
                $("#submit-145").css('display', 'none');
            })

            .success(function (successInfo) {
                if (successInfo !== 'ok') {
                    var compiledInfo, key, column;
                    if (typeof successInfo !== 'string') {
                        compiledInfo = '<table id="sortered" ' +
                            'class="table table-striped table-bordered table-condensed">';

                        if (typeof successInfo[0] == 'object') {
                            compiledInfo += '<thead><tr>';
                            for (key in successInfo[0]) {
                                compiledInfo += '<th>' + key + '</th>';
                            }
                            compiledInfo += '</tr></thead>';
                        }

                        compiledInfo += '<tbody>';

                        for (key in successInfo) {
                            if (typeof successInfo[key] == 'object') {
                                compiledInfo += '<tr>';
                                for (column in successInfo[key]) {
                                    compiledInfo += '<td>' + successInfo[key][column] + '</td>';
                                }
                                compiledInfo += '</tr>';
                            }
                            else {
                                compiledInfo += '<tr><td>' + successInfo[key] + '</td></tr>';
                            }
                        }

                        compiledInfo += '</tbody></table>';
                    }
                    else {
                        compiledInfo = successInfo;
                    }

                    $("#json-result").html(compiledInfo).fadeIn();
                    $("#sortered").tablesorter();
                }
                else {
                    $("#method-interface").prepend(
                        '<div class="alert alert-success">' +
                            '<a class="close" data-dismiss="alert">×</a>' +
                            '<strong>Well done!</strong> Executed success.</div>'
                    );
                }
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