

 <div class="modal fade bs-example-modal-sm" id="create-alert" tabindex="-1" role="dialog" aria-labelledby="Create" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h5 class="modal-title" id="Create">Alert Rules</h5>
        </div>
        <div class="modal-body">
            <form method="post" role="form" id="rules" class="form-horizontal alerts-form">
            <div class="row">
                <div class="col-md-12">
                    <span id="response"></span>
                </div>
            </div>
            <input type="hidden" name="device_id" id="device_id" value="">
            <input type="hidden" name="type" id="type" value="create-alert-item">
        <div class="form-group">
                <label for='entity' class='col-sm-3 control-label'>Entity: </label>
                <div class="col-sm-5">
                        <input type='text' id='suggest' name='entity' class='form-control' placeholder='I.e: devices.status'/>
                </div>
        </div>
        <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                        <p>Start typing for suggestions, use '.' for indepth selection</p>
                </div>
        </div>
        <div class="form-group">
                <label for='condition' class='col-sm-3 control-label'>Condition: </label>
                <div class="col-sm-5">
                        <select id='condition' name='condition' placeholder='Condition' class='form-control'>
                                <option value='='>Equals</option>
                                <option value='!='>Not Equals</option>
                                <option value='>'>Larger than</option>
                                <option value='>='>Larger than or Equals</option>
                                <option value='<'>Smaller than</option>
                                <option value='<='>Smaller than or Equals</option>
                        </select>
                </div>
        </div>
        <div class="form-group">
                <label for='value' class='col-sm-3 control-label'>Value: </label>
                <div class="col-sm-5">
                        <input type='text' id='value' name='value' class='form-control'/>
                </div>
        </div>

        <div class="form-group">
                <label for='rule-glue' class='col-sm-3 control-label'>Connection: </label>
                <div class="col-sm-5">
                        <button class="btn btn-default btn-sm" type="submit" name="rule-glue" value="&&" id="and" name="and">And</button>
                        <button class="btn btn-default btn-sm" type="submit" name="rule-glue" value="||" id="or" name="or">Or</button>
                </div>
        </div>
        <div class="form-group">
                <label for='severity' class='col-sm-3 control-label'>Severity: </label>
                <div class="col-sm-5">
                        <select name='severity' placeholder='Severity' class='form-control'>
                                <option value='ok'>OK</option>
                                <option value='warning'>Warning</option>
                                <option value='critical' selected>Critical</option>
                        </select>
                </div>
        </div>
        <div class="form-group">
                <div class="col-sm-offset-3 col-sm-3">
                        <button class="btn btn-default btn-sm" type="submit" name="rule-submit" id="rule-submit" value="save">Save Rule</button>
                </div>
        </div>
</form>
                        </div>
                </div>
        </div>
</div>
<script>

$('#create-alert').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var device_id = button.data('device_id');
    var modal = $(this)
    $('#device_id').val(device_id);
});
</script>

<script>
var cache = {};
$('#suggest').typeahead([
    {
      name: 'suggestion',
      remote : '/ajax_rulesuggest.php?device_id=<?php echo $device['device_id'];?>&term=%QUERY',
      template: '<p>{{name}} - {{current}}</p>',
      valueKey:"name",
      engine: Hogan
    }
]);

$('#and, #or').click('', function(e) {
    e.preventDefault();
    var entity = $('#suggest').val();
    var condition = $('#condition').val();
    var value = $('#value').val();
    var glue = $(this).val();
    if(entity != '' && condition != '') {
        $('#response').tagmanager({
           strategy: 'array',
           tagFieldName: 'rules[]'
        });
        $('#response').data('tagmanager').populate([ '%'+entity+' '+condition+' '+value+' '+glue ]);
    }
});

$('#rule-submit').click('', function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/ajax_form.php",
        data: $('form.alerts-form').serialize(),
        success: function(msg){
            $("#message").html('<div class="alert alert-info">'+msg+'</div>');
            $("#create-alert").modal('hide');
            if(msg.indexOf("ERROR:") <= -1) {
                $('#response').data('tagmanager').empty();
            }
        },
        error: function(){
            $("#message").html('<div class="alert alert-info">An error occurred creating this alert.</div>');
            $("#create-alert").modal('hide');
        }
    });
});

</script>
