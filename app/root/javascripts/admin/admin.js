require(["dojo/dom", "dojo/_base/xhr", "dojo/domReady!"], function(dom, xhr)
{
    /**
     * 1. Get admin content
     * 2. If unauth - init auth form
     * 3. On auth if 403 - display error
     * 4. If auth - get admin content
     */
    dom.byId('greeting').innerHTML = '';
    dom.byId('auth-form').onsubmit = function()
    {
        var inputs = this.getElementsByTagName('input');
        if ((inputs[0].value.length > 0) && (inputs[1].value.length > 0))
        {
            xhr.get(
            {
                url: "/admin/auth",
                content:
                {
                    login: inputs[0].value,
                    password: inputs[1].value
                },

                load: function(result)
                {
                    alert(result);
                },

                error: function(result)
                {
                    if (result.status == 403)
                    {
                        alert('Wrong login or password');
                    }
                }
            });
        }

        return false;
    }
});