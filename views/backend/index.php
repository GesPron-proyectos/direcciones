<?php $this->load->view('backend/a_common/head'); ?>

<body>

<div class="wrapper">

<!--R-CONT-->

<div class="wrapper-rcont">

	<!--header-->

    <?php $this->load->view('backend/a_common/header'); ?>

    <div class="clear"></div>

    <!--sec-m-menu-->

    <!--content-->

    <div id="not"></div>

    <div class="content" id="content">
	<?php $this->load->view('backend/templates/'.$plantilla); ?>

    </div>

    <!--content--> 

    <!--footer-->

    <div class="footer">

<?php $this->load->view('backend/a_common/footer'); ?>

    </div>

    <!--footer-->

</div> 

<!--R-CONT-->

<div class="clear"></div>   

</div>

</body>

</html>
