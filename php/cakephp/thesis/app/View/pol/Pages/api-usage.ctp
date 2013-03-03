<div class="well">


    <h2>Jak używać API</h2>
    <br />
    <h4>1. Jak użyć API (płatnego) ?</h4>
    <p>W najprostszej formie potrzebujesz jedynie zapytaina:
        <code>GET http://apigeum.com/&lt;nazwa-repozytorium&gt;</code>
    </p>

    <p>Dodatkowo możesz ograniczyć rezultat zapytania, poprzez użycie parametrów:
<code>
        limit,
        offset,
        order,
        fields</code>
    </p>

    <p>
        <code>GET http://apigeum.com/&lt;your-repository-name&gt;?limit=10&offset=20&fields=field1,field2&order=field1,asc</code>
    </p>

    <p>Kiedy używasz płatnego API, musisz podać klucz który otrzymałeś po zakupie:</p>
    <p>
        <code>GET http://apigeum.com/&lt;nazwa-repozytorium&gt;?key=&lt;twoj-klucz-api&gt;</code>
    </p>

    <p>Takie zapytanie można również ograniczyć: </p>

    <p>
        <code>GET http://apigeum.com/&lt;your-repository-name&gt;?key=&lt;twoj-klucz-api&gt;&limit=10&offset=20&fields=field1,field2&order=field1,asc</code>
    </p>


    <h4>2. Jak użyć API (darmowego) ?</h4>
    Tak samo jak płatnego, tylko bez parametru key.

    <h4>3. Jestem autorem API, chcę miec dostęp do CREATE, PUT, DELETE.</h4>
    W tym celu wymagana jest autoryzacja poprzez nagłówek
    <code>Authorization: Basic base64(email:password)</code> przy każdym zapytaniu.

</div>