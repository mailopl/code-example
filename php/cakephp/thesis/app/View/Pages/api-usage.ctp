<div class="well">

    <h2>How to use the API</h2>
    <br />
    <legend>1. How to use the premium API ? </legend>
    <p>In the simplest form you just need to perform a request like:

        <code>GET <?php echo $this->Html->url('/', true) ?>&lt;your-repository-name&gt;</code>
    </p>

    <p>You can additionally limit your query results by using

        <code>limit,offset, order,fields </code>
    </ul>
    </p>

    <p>
        <code>GET <?php echo $this->Html->url('/', true) ?>&lt;your-repository-name&gt;?limit=10&offset=20&fields=field1,field2&order=field1,asc</code>
    </p>

    <p>When you use commercial API, you need to provide an API key you received after purchase:</p>
    <p>
        <code>GET <?php echo $this->Html->url('/', true) ?>&lt;your-repository-name&gt;?key=&lt;your_api_key&gt;</code>
    </p>

    <p>And of course you can limit that query too: </p>

    <p>
        <code>GET <?php echo $this->Html->url('/', true) ?>&lt;your-repository-name&gt;?key=&lt;your_api_key&gt;&limit=10&offset=20&fields=field1,field2&order=field1,asc</code>
    </p>



    <legend>2. How to use the free API?</legend>
    Just like the premium, but do not use the key parameter.



    <legend>3. I'm the repository owner  and I want access to CREATE, PUT, DELETE methods.</legend>
    In that case you need Authorization: Basic header
    <code>Authorization: Basic base64(email:password)</code> in every request. Email and password are the same you use to log in.



        <legend><?php echo __('4. How do I use the REST API?'); ?></legend>
        <div class="summary-api">
            <table class="table table-bordered table-hover table-striped table-condensed">
                <thead>
                <tr>
                    <th><?php echo __('Method'); ?></th>
                    <th><?php echo __('URL'); ?></th>
                    <th><?php echo __('Description'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>GET</td><td><?php echo $this->Html->url('/', true) ?>api/<span class="variable-title">:repository-name</span>/</td>
                    <td><?php echo __('Retrieves the rows list'); ?></td>
                </tr>
                <tr>
                    <td>POST</td><td><?php echo $this->Html->url('/', true) ?>api/<span class="variable-title">:repository-name</span>/</td>
                    <td><?php echo __('Creates new row'); ?></td>
                </tr>
                <tr>
                    <td>GET</td><td><?php echo $this->Html->url('/', true) ?>api/<span class="variable-title">:repository-name</span>/:id</td>
                    <td><?php echo __('Retrieves particular row'); ?></td>
                </tr>
                <tr>
                    <td>PUT</td><td><?php echo $this->Html->url('/', true) ?>api/<span class="variable-title">:repository-name</span>/:id</td>
                    <td><?php echo __('Updates or creates (if doesn\'t exist) a row'); ?></td>
                </tr>
                <tr>
                    <td>DELETE</td><td><?php echo $this->Html->url('/', true) ?>api/<span class="variable-title">:repository-name</span>/:id</td>
                    <td><?php echo __('Deletes particular row'); ?></td>
                </tr>
                </tbody>
            </table>
        </div>


</div>