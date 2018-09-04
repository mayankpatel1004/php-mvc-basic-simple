<html>
    <head>
        <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
    </head>
    <body>
        <?php global $site_url; ?>
        <a href="index.php?pg=home">Home</a>
        <a href="index.php?pg=contact">Contact</a>
        <form method="post" id="contact_form" enctype="multipart/form-data" onsubmit="fnFormvalidation()">
            <?php
            $maxlength = "100";
            $value = "";
            $default_class = "form-control";

            function convertLabel($string) {
                $finalstring = ucwords(str_replace("_", " ", $string));
                $finalstring = ucwords(str_replace("-", " ", $finalstring));
                return $finalstring;
            }

            foreach ($field_array as $fields) {
                $inputtype = isset($fields['type']) && $fields['type'] != "" ? $fields['type'] : "text";
                $label = isset($fields['label']) && $fields['label'] != "" ? $fields['label'] : ucfirst($fields['name']);
                $class = isset($fields['class']) && $fields['class'] != "" ? $fields['class'] : "";
                $fieldname = isset($fields['name']) && $fields['name'] != "" ? $fields['name'] : "";
                $multiple_selection = isset($fields['multiple']) && $fields['multiple'] == 'multiple' ? "multiple=multiple" : "";

                if ($inputtype == 'text' || $inputtype == 'hidden') {
                    if (isset($fieldname) && $fieldname != "") {
                        ?>
                        <?php if ($inputtype != "hidden"): ?><label><?php echo convertLabel($label); ?></label><?php endif; ?>
                        <input type="<?php echo $inputtype; ?>" title="<?php echo $fieldname; ?>" maxlength="<?php echo $maxlength; ?>" class="<?php echo $default_class . " " . $class; ?>" name="<?php echo $fieldname; ?>" id="<?php echo $fieldname; ?>" value="<?php echo $value; ?>" />
                        <div id="<?php echo $fieldname . "_error"; ?>"></div>
                        <?php
                    }
                }
                if ($inputtype == 'file') {
                    if (isset($fieldname) && $fieldname != "") {
                        ?>
                        <label><?php echo convertLabel($label); ?></label>
                        <input type="<?php echo $inputtype; ?>" title="<?php echo $fieldname; ?>" class="<?php echo $default_class . " " . $class; ?>" name="<?php echo $fieldname; ?>" id="<?php echo $fieldname; ?>" />
                        <div id="<?php echo $fieldname . "_error"; ?>"></div>
                        <?php
                    }
                }
                if ($inputtype == 'select') {
                    if (isset($fieldname) && $fieldname != "") {
                        ?>
                        <label><?php echo convertLabel($label); ?></label>
                        <select title="<?php echo $fieldname; ?>"  maxlength="<?php echo $maxlength; ?>" class="<?php echo $default_class . " " . $class; ?>" name="<?php echo $fieldname; ?><?php
                        if ($multiple_selection != "") {
                            echo "[]";
                        }
                        ?>" id="<?php echo $fieldname; ?>" <?php echo $multiple_selection; ?>>
                            <option value="">Select Option</option>
                            <?php
                            if (isset($fields['options']) && $fields['options'] != false) {
                                foreach ($fields['options'] as $key => $value) {
                                    ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                                }
                            }
                            ?>
                        </select>
                        <div id="<?php echo $fieldname . "_error"; ?>"></div>
                        <?php
                    }
                }
                if ($inputtype == 'radio') {
                    if (isset($fieldname) && $fieldname != "") {
                        ?>
                        <label><?php echo convertLabel($label); ?></label>

                        <?php
                        if (isset($fields['options']) && $fields['options'] != false) {
                            foreach ($fields['options'] as $key => $value) {
                                ?><Br /><input type="<?php echo $inputtype; ?>" title="<?php echo $fieldname; ?>" class="<?php echo $default_class . " " . $class; ?>" name="<?php echo $fieldname; ?>" id="<?php echo $fieldname; ?>" /><label><?php echo $fieldname; ?></label><Br /><?php }
                            ?><div id="<?php echo $fieldname . "_error"; ?>"></div><?php
                        }
                    }
                }
                if ($inputtype == 'textarea') {
                    if (isset($fieldname) && $fieldname != "") {
                        ?>
                        <label><?php echo convertLabel($label); ?></label>
                        <textarea name="<?php echo $fieldname; ?>" class="<?php echo $default_class . " " . $class; ?>" id="<?php echo $fieldname; ?>"><?php echo $value; ?></textarea>
                        <div id="<?php echo $fieldname . "_error"; ?>"></div>
                        <?php
                    }
                }
            }
            ?>

            <input type="button" onclick="return fnSavedata();" value="Save" />
        </form>
        <script type="text/javascript">
            function fnSavedata() {
                sdata = $("#contact_form").serialize();
                $.ajax({
                    url: '<?php echo $site_url . "contact/save/"; ?>', // point to server-side PHP script 
                    dataType: 'json',
                    cache: false,
                    data: sdata,
                    type: 'POST',
                    success: function (result) {
                        $('#contact_form .validaterequiredfield').each(function(){
                            var variables = $(this).attr('title');
                            alert(result.variables);
//                            if($(result.$(this).attr('title')).length > 0){
//                                alert(result.$(this).attr('title'));
//                            }
                        });
                    }
                });
            }
            function fnValidateform() {
                var erroroccur = 0;
                $('.validaterequiredfield').each(function () {
                    if ($(this).val() == '') {
                        $("#" + $(this).attr('id') + "_error").html("This is required.");
                        erroroccur = 1;
                    }
                });
                $(".validateemailfield").each(function () {
                    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                    if (reg.test(emailField.value) == false) {
                        $("#" + $(this).attr('id') + "_error").html("Please enter valid email address.");
                        erroroccur = 1;
                    }
                });
            }
            $(document).ready(function () {
                $('#tmp_upload_document').on('change', function () {
                    var file_data = $('#tmp_upload_document').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        url: '<?php echo $site_url . "contact/savefile/"; ?>', // point to server-side PHP script 
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (response) {
                            $("#upload_document").val(response.filename);
                        }
                    });
                });
                $('#tmp_upload_image').on('change', function () {
                    var file_data = $('#tmp_upload_image').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        url: '<?php echo $site_url . "contact/savefile/"; ?>', // point to server-side PHP script 
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (response) {
                            $("#upload_image").val(response.filename);
                        }
                    });
                });
            });
        </script>
    </body>
</html>