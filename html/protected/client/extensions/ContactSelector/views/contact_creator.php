<div id="new_contact_form" class="form">
    
    <p class="helpText">Fill in the fields below to create a new Contact. Fields with *asterisks are required. If Email or Phone Number are left blank, the buttons in the app will not be active. Click “Save” when you are done making changes or adding new content.</p>

    <div class="row-fluid">

        <div class="span6">
            <label for="first_name" class="required">First Name <span class="required">*</span></label>
            <?php
            echo Chtml::textField('first_name', '', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
            ?>
        </div>

        <div  class="span6">
            <?php
            echo Chtml::label('Last Name', 'last_name');
            echo Chtml::textField('last_name', '', array('size' => 60, 'class' => 'span11'));
            ?>
        </div>
    </div>

    <div class="row-fluid">

        <div class="span6">
            <?php
            echo Chtml::label('Email', 'email');
            echo Chtml::textField('email', '', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
            ?>
        </div>

        <div  class="span6">
            <?php
            echo Chtml::label('Title', 'title');
            echo Chtml::textField('title', '', array('size' => 60, 'class' => 'span11'));
            ?>
        </div>
    </div>


    <div class="row-fluid">
        <div class="span6">
            <?php
            echo Chtml::label('Phone Number', 'phone_number');
            echo Chtml::textField('phone_number', '', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
            ?>
        </div>
    </div>
    
    <div id="contact_error" style="display:none;" class="errorSummary"></div>

</div>