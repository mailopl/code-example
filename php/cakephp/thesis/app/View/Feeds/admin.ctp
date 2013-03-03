<script type="text/javascript">

    window.onload = function() {
        (function poll(){
            $.ajax({ url: "http://localhost/apigeum/feeds/websocket", success: function(data){

                $("#keys tbody tr").remove();
                var text = [];
                var i = 0;
                for(var a = 0, len =  data.length; a < len; a+=1){
                    text[i++] = "<tr><td>";
                    text[i++] = data[a].key;
                    text[i++] = "(";
                    text[i++] = data[a].slug;
                    text[i++] = ")</td><td>";
                    text[i++] = data[a].requests;
                    text[i++] = "</td></tr>";
                }

                $("#keys tbody").append(text.join(''));



            }, dataType: "json", complete: poll, timeout: 3000 });
        })();
    }


</script>
<h2>Premium repositories with ending requests</h2>
<table id="keys" class="tablesorter table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Requests left</th>
    </tr>
    </thead>

    <tbody>

    </tbody>
</table>