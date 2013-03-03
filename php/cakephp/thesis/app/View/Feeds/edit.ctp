<div class="feeds form" xmlns="http://www.w3.org/1999/html">
    <?php echo $this->Form->create('Feed'); ?>
    <?php
    /*
    |--------------------------------------------------------------------------
    | Datatables initialization javascript
    |--------------------------------------------------------------------------
     */
    ?>
    <?php
        $body =
        <<<STRING
            var table = $("#table_id").dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sAjaxSource": webroot  +"api/dt/{$data['Feed']['slug']}/",
                "sPaginationType": "bootstrap",

                "aoColumns": [
STRING;

        $dynamic = null;
        foreach( $columns as $column):
            $dynamic .= !empty($column) ? ' { "mData": "'. $column.'" },' : null;
        endforeach;
    ?>

    <?php echo $this->Html->scriptBlock( $body . $dynamic .
        '    ]
        }).makeEditable({
            sUpdateURL: webroot + "api/dt/' . $data['Feed']['slug'] .'/",
            sUpdateHttpMethod: "PUT",

            sAddURL: webroot + "api/dt/' . $data['Feed']['slug'] . '",
            sAddHttpMethod: "POST",

            sDeleteURL: webroot +"api/dt/' . $data['Feed']['slug'] .'/",
            sDeleteHttpMethod: "DELETE",

            oAddNewRowButtonOptions: {
                class: "btn btn-primary"
            },
            fnOnDeleted: function() {
                table.fnClearTable();
            },
            fnEndProcessingMode: function () {
                $("#formAddNewRow").dialog("close");
                $("#processing").hide();
            },
            fnStartProcessingMode: function () {
                $("#processing").dialog();
            },
        });',
        array(
            'inline' => false,
            'block'=>'scriptBottom'
        ))
    ?>

    <div id="processing_message" class="hide" title="Processing">
        <?php echo __('Please wait while your request is being processed...'); ?>
    </div>

    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: feed data
    |--------------------------------------------------------------------------
    */
    ?>
    <fieldset>
		<legend><?php echo __('1. Edit your repository name and description'); ?></legend>
        <div class='alert alert-info'>
            <?php echo __('It is strongly recommended to use plural and concrete verb (ex. beagles instead of dog).'); ?>
        </div>

        <?php
            echo $this->Form->input('id');
            echo $this->Form->label(
                'name',
                __('Repository ') .  ' ' . $data['Feed']['name'] . ' ( '. $this->Html->url('/', true) . h($data['Feed']['slug']).' )',
                array(
                    'title'=>__('Cannot rename the repository!')
                )
            );
        ?>
        <?php
            echo $this->Form->input('description', array(
                'label'=>false,
                'placeholder'=> __('Description')
            ));
            echo $this->Form->input('Schema.id',array(
                'value' => $schema['Schema']['_id'],
                'type'=>'hidden'
            ));

            echo $this->Form->input('type', array(
                'label'=>false,
                'type'=>'select',
                'options' => array(
                    'free' => __('Free'),
                    'premium' => __('Premium').' ('. Configure::read('Premium.requestPrice') . __('$/100k request').')'
                )
            ));
            echo $this->Form->input('completed', array(
                'label' => false,
                'placeholder' => __('Status'),
                'options' => array(
                    0 => __('Not completed'),
                    1 => __('Completed')
                )
            ));
        ?>
    </fieldset>
    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: API fields
    |--------------------------------------------------------------------------
    */
    ?>
    <fieldset style="width:800px;">
        <legend>
            <?php echo __('2. Edit column names'); ?>
            <?php echo $this->Form->error('fields', null, array('class' =>'red help-inline')) ?>
            <a href="#add-column" id="addColumn" class="btn btn-mini"><i class="icon icon-plus-sign"></i>Add column</a>
        </legend>
        <?php
            $i = 0;
            foreach( $columns as $column):
        ?>
            <div class="single-column">
            <?php
                echo $this->Form->input('Schema.old.'.$column, array(
                    'label'=>false,
                    'class'=>'column',
                    'type'=>'text',
                    'div'=>false,
                    'value' => $column
                ));
                ?>
                <img data-toggle="modal" href="#modal<?php echo $i ?>" rel="<?php echo $i ?>"
                    role="button"
                    src="<?php echo $this->webroot ?>img/menu_dropdown.png" />

                <?php echo $this->element('_dropdownModal', array(
                        'id' => $i,
                        'name' => $column,
                        'title' => __('Dropdown values for - ') . $column,
                        'value' => isset($defaults[$column]) ? $defaults[$column] : null
                )) ?>
            </div>
        <?php
         $i++;
         endforeach;
        ?>

        <?php for($i, $c = count( $columns); $i < 10; ++$i): ?>
            <div class="single-column hide">
                <?php
                    echo $this->Form->input('Schema.new.'.$i.'', array(
                        'label'=>false,
                        'class'=>'column',
                        'type'=>'text',
                        'div'=>false,
                        'placeholder'=> __('Default name')
                    ));
                ?>
                <img data-toggle="modal" href="#modal<?php echo $i ?>" rel="<?php echo $i ?>" role="button"
                     src="<?php echo $this->webroot ?>img/menu_dropdown.png"/>

                <?php echo $this->element('_dropdownModal', array(
                    'id' => $i,
                    'title' => __('Dropdown values'),
                    'value' => null
                )) ?>
            </div>
        <?php endfor; ?>
	</fieldset>
    <script type="text/javascript">
        var visibleFields = <?php echo max(0, $c-1) ?>;
        document.ready = function(){
            $("#addColumn").click(function(e){
                e.preventDefault();
                visibleFields++;
                if (visibleFields > 9) {
                    alert("There is 10 columns limit. Sorry.");
                }

                $($(".single-column")[visibleFields]).removeClass("hide");
            });
        };
    </script>

    <br />
    <?php echo $this->Form->submit(__('Save changes'),array('class'=>'btn btn-primary')); ?>
    <?php echo $this->Form->end(); ?>
    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: Preview of routes
    |--------------------------------------------------------------------------
    */
    ?>

    <br />
    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: Data
    |--------------------------------------------------------------------------
    */
    ?>
    <fieldset>
        <legend><?php echo __('3. Edit the data'); ?></legend>
    ​   <div class="csv-file">
            <span class="button btn btn-success"><?php echo __('Import CSV'); ?></span>
            <form id="import-csv" enctype="multipart/form-data">
                <input type="file" id="file" />
            </form>
        </div>

        <?php
        /*
        |--------------------------------------------------------------------------
        | Form: Ajax CSV Import
        |--------------------------------------------------------------------------
        */
        ?>
        <script type="text/javascript">
               window.onload = function(){
                    document.getElementById('file').addEventListener('change', function(e) {
                        var
                            xhr = new XMLHttpRequest(),
                            file = this.files[0],
                            formData = new FormData,
                            feed_id = $("#FeedId").val();

                        formData.append('file', file);
                        formData.append('feed_id', feed_id);

                        if ($("#file").val()){
                            $(".csv-file").find(".button").html(
                                    "<img src='" + webroot + "/img/ajax.gif' />"
                            );
                        }

                        xhr.onreadystatechange = function(e) {
                            if ( 4 == this.readyState ) {
                                alert("Import complete");

                                $("#file").val("");
                                $(".csv-file").find(".button").html("Import CSV");

                                table.fnClearTable();
                            }
                        };
                        xhr.open('post', webroot + "/feeds/import", true);
                        xhr.send(formData);
                    }, false);
                };
        </script>

        <div style="float:right;" class="add_delete_toolbar"></div>
        <div style="clear:both;"></div>
        <br />

        <?php
        /*
        |--------------------------------------------------------------------------
        | Form: Datatable
        |--------------------------------------------------------------------------
        */
        ?>
        <table id="table_id" class="table table-bordered table-hover table-striped grid">
            <thead>
                <tr>
                    <?php foreach($columns  as $column): ?>
                        <?php if(empty($column)) continue ?>
                        <th><?php echo $column ?> </th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody> </tbody>
        </table>
    </fieldset>

    <fieldset>
        <legend>
            <?php echo __('4. Mark the data completed by clicking '); ?>
            <i class="icon icon-ok-circle"></i>
            <?php echo __('on your repositories list'); ?>
        </legend>
    </fieldset>


    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: Modal with "add new row"
    |--------------------------------------------------------------------------
    */
    ?>

    <form id="formAddNewRow" action="#" title="<?php echo __('Add new record'); ?>">
    <?php
        $i = 0;
        foreach($columns  as $column):
            if(empty($column)) continue;
    ?>
            <label><?php echo $column ?></label>
            <input type="text" name="Row[<?php echo $column ?>]" id="<?php echo $column ?>" rel="<?php echo $i ?>" />

            <?php if (isset($defaults[$column])): ?>
                <?php echo $this->Html->scriptBlock(
                    "$('#$column').typeahead({source:['". join('\',\'', $defaults[$column]) . "']});",
                    array(
                        'inline' => false,
                        'block'=>'scriptBottom'
                    ))
                ?>
            <?php endif ?>
        <?php
            ++$i;
            endforeach;
        ?>
        <input type="hidden" name="Row[feed_id]" value="<?php echo $data['Feed']['id'] ?>" />
    </form>
</div>