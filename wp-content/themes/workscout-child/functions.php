<?php

ob_start();

$user = wp_get_current_user(); 

include('custom_functions.php');

include('doc_functions.php');

function checkPackageTpye1(){

	global $woocommerce;

    $items = $woocommerce->cart->cart_contents;

	if(!empty($items)){

		foreach($items as $item => $values) { 

			$package = get_post_meta($values['product_id'] , 'package_type', true);

		}

		return $package;

	}else{

		return false;

	}

}

add_action( 'wp_enqueue_scripts', 'workscout_enqueue_styles' );

function workscout_enqueue_styles() {

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css',array('workscout-base','workscout-responsive','workscout-font-awesome') );

    wp_enqueue_style( 'creeess-style', get_stylesheet_directory_uri() . '/js/owl.carousel.css');

	 wp_enqueue_script('custom-matchhe', get_stylesheet_directory_uri() . '/js/jquery.matchHeight-min.js');

	 wp_enqueue_script('custom-cross', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js');

	 wp_enqueue_script('save-cross', get_stylesheet_directory_uri() . '/js/filejs.js');

}	

	

function remove_parent_theme_features() {	



}

add_action( 'after_setup_theme', 'remove_parent_theme_features', 10 );

add_filter( 'submit_resume_form_fields', 'remove_submit_resume_form_fields' );



function remove_submit_resume_form_fields( $fields ) {

	// Unset any of the fields you'd like to remove - copy and repeat as needed

	unset( $fields['resume_fields']['candidate_education']['fields']['location'] );

	unset( $fields['resume_fields']['candidate_education']['fields']['qualification'] );

	unset( $fields['resume_fields']['candidate_education']['fields']['date'] );

	unset( $fields['resume_fields']['candidate_education']['fields']['notes'] );

	//unset( $fields['resume_fields']['candidate_education']['fields']['Dob'] );

	

	unset( $fields['resume_fields']['candidate_experience']['fields']['employer'] );

	unset( $fields['resume_fields']['candidate_experience']['fields']['job_title'] );

	unset( $fields['resume_fields']['candidate_experience']['fields']['date'] );

	unset( $fields['resume_fields']['candidate_experience']['fields']['notes'] );

	unset( $fields['resume_fields']['job_type']);

	//unset( $fields['resume_fields']['candidate_experience']['fields']['Dob'] );	

	// And return the modified fields

	$fields['resume_fields']['job_type'] = array(

	'label'         => __( 'Searching  Job Level/Type', 'wp-job-manager-resumes' ),

	'type'        => 'select',

	'required'      => true,

	'priority'      => 27,

	'options' => array(''=>'--Select--','Full time'=>'Full time','Per time'=>'Per time','contractual'=>'Contractual','freelancer'=>'Freelancer','internship'=>'Internship'),

	'personal_data' => true,

	);

	return $fields;	

}



add_filter( 'submit_job_form_fields', 'frontend_add_jobpost_field' );



function frontend_add_jobpost_field( $fields ) {

$user = wp_get_current_user();

if ( job_manager_multi_job_type() ) {

	$job_type = 'term-multiselect';

	} else {

	$job_type = 'term-select';

}

unset( $fields['job']['job_description'] );

unset($fields['job']['job_type']);

if( isset( $_GET['action'] ) && $_GET['action'] =='edit' && !empty( $_GET['job_id'] ) && is_numeric( $_GET['job_id'] ) ){

	$package_plan_type = get_post_meta( $_GET['job_id'],'package_plan_type',true);	

}

if( isset($_COOKIE['package_type']) && $_COOKIE['package_type'] =='free' || (!empty($package_plan_type) && $package_plan_type=='free')){

	//unset($fields['job']['apply_link']);

	unset($fields['company']['company_website']);

	unset($fields['company']['company_logo']);	

}	

//unset($fields['job']['header_image']);

//$fields['job']['job_description'] = array('required'    => true);	

$fields['job']['job_type'] = array('label'=> __( 'Job Type', 'wp-job-manager' ),'type' => $job_type,

'required'    => true,'placeholder' => __( 'Choose job type&hellip;', 'wp-job-manager' ),'priority'    => 3,

'default'     => '','taxonomy'    => 'job_listing_type');

					

$fields['job']['job_description'] = array('label'    => __( 'Job Description', 'wp-job-manager' ),					'type'     => 'wp-editor','required' => false,'priority' => 5);

					

$fields['job']['upload'] = array( 'label'  => __( 'Upload Job Circular Image/Document', 'job_manager' ), 'type'  => 'file', 'ajax' => true, 'multiple'  => false,   'priority'    => 6 , 'class' => 'upload-btn',  'allowed_mime_types' => array( 'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif'  => 'image/gif', 'png'  => 'image/png') ); 

  	

$fields['job']['vacancy'] = array(    'label'       => __( 'Vacancy', 'job_manager' ),    'type'        => 'text',    'required'    => false,    'placeholder' => 'e.g. 5',    'priority'    => 7  ); 

 

$fields['job']['educational_req'] = array(    'label' => __( 'Educational Requirements', 'job_manager' ),   'type'        => 'wp-editor',  'personal_data' => true, 'required'    => false,  'priority'    => 8); 



$fields['job']['experience_req'] = array(    'label'       => __( 'Experience Requirements', 'job_manager' ),    'type'        => 'wp-editor', 'personal_data' => true,   'required'    => false, 'priority'    => 9  ); 



$fields['job']['other'] = array(    'label'       => __( 'Other Requirements', 'job_manager' ), 'type'        => 'wp-editor',  'required'    => false, 'personal_data' => true, 'priority'    => 11  ); 



$fields['job']['procedure'] = array(    'label'       => __( 'Application Procedure', 'job_manager' ), 'type'        => 'wp-editor',  'required'    => false, 'personal_data' => true, 'priority'    => 11  ); 



$fields['job']['source'] = array(    'label'       => __( 'Source', 'job_manager' ),  'type' => 'text',    'required'    => false,    'placeholder' => '',    'priority'    => 11  ); 



$fields['job']['company_type'] = array(

	    'label' => __( 'Company Type', 'job_manager' ),

	    'type' => 'select',

		'options' =>array('' =>'--Select--','private' => 'Private Company', 'govt' => 'Government Company'),

	    'required' => true,

	    'placeholder' => '',

	    'priority' => 0

	);
// modify by Jfrost
$fields['job']['exp_range'] = array(

		'label' => __( 'Experience Range', 'job_manager' ),

		'type' => 'select',

	  'options' =>array( 
			'any' => 'Any' , 
			'below_1' => 'Below 1 year' ,
			'1_3' => '1 - < 3 years' ,
			'3_5' => '3 - < 5 years' ,
			'5_10' => '5 - < 10 years' , 
			'over_10' => 'Over 10 years'),

		'required' => false,

		'placeholder' => '',

		'priority' => 9

);

/* $fields['job']['Dob'] = array(    'label'       => __( 'Dob', 'job_manager' ),    'type'  => 'date',    'required'    => false,    'placeholder' => 'YY-MM-DD',    'priority'    => 11 );  */



return $fields;

}



add_filter( 'job_manager_job_listing_data_fields', 'admin_add_jobpost_field' );



function admin_add_jobpost_field( $fields ) {  



	$fields['_upload'] = array( 'label'  => __( 'Upload Job Circular Image/Document', 'job_manager' ), 'id' => '_upload', 'type'  => 'file', 'ajax' => true);	



	$fields['_vacancy'] = array( 'label'       => __( 'Vacancy', 'job_manager' ),    'type' => 'text',    'required'    => false,    'placeholder' => 'e.g. 5',    'description'  => ''  );  

  

	$fields['_educational_req'] = array('label' => __( 'Educational Recruitment', 'job_manager' ),    'type' => 'textarea', 'personal_data' => true, 'description'    => ''  ); 

	$fields['_experience_req'] = array('label' => __( 'Experience Requirements', 'job_manager' ),    'type'  => 'textarea', 'description'    => ''  ); 

	$fields['_other'] = array('label' => __( 'Other Requirements', 'job_manager' ),  'type' => 'textarea', 'description'   => ''  ); 

	$fields['_procedure'] = array('label' => __( 'Application Procedure', 'job_manager' ),  'type' => 'textarea', 'description'   => ''  ); 

	

	$fields['_source'] = array('label' => __( 'Source', 'job_manager' ),  'type' => 'text', 'description'   => ''  ); 

	

	$fields['_company_type'] = array(

	    'label' => __( 'Company Type', 'job_manager' ),

		'id' => '_company_type',

	    'type' => 'select',

		'options' =>array('' =>'--Select--','private' => 'Private Company', 'govt' => 'Government Company')

		);



	/* $fields['_Dob'] = array('label' => __( 'Dob', 'job_manager' ),  'text'  => 'date', 'description'    => ''  ); */

	return $fields;

}

add_action( 'single_job_listing_meta_end', 'display_jobpost_customfield_data' );

function display_jobpost_customfield_data() {

  global $post;

  $vacancy = get_post_meta( $post->ID, '_vacancy', true ); 

  $Dob = get_post_meta( $post->ID, '_Dob', true );  



  if ( $vacancy ) {

    echo '<li>

		<i class="fa fa-user" aria-hidden="true"></i>

		<div>

			<strong>' . __( 'Vacancy:','workscout' ) .'</strong>

			<span>'.$vacancy. '</span>

		</div>

	</li>';

  } 

  

    

 /*  if ( $Dob ) {

    echo '<li>

		<i class="fa fa-user" aria-hidden="true"></i>

		<div>

			<strong>' . __( 'Dob:','workscout' ) .'</strong>

			<span>'.$Dob. '</span>

		</div>

	</li>';

  } */

}



// Add field to frontend submit resume

add_filter( 'submit_resume_form_fields', 'wpjms_frontend_resume_form_fields' );

function wpjms_frontend_resume_form_fields( $fields ) {	



         $fields['resume_fields']['key_word_skill']  = array(

                    'label'         => __('Key Ward(e.g. Preferred job listings)', 'wp-job-manager-resumes' ),	   

					'type'          => 'select',

					'attr'			=> array('multiple'=>'multiple'),					

					'required'      => false,

					'priority'      => 28,

                    'options'       => getKeyWard(),

					'personal_data' => true,

				); 

	/* $fields['resume_fields']['expected_salary'] = array(

	    'label' => __( 'Expected Salary', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => '',

	    'priority' => 12

	); */

	/* $fields['resume_fields']['training'] = array(

	    'label' => __( 'Training', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => '',

	    'priority' => 12

	);

	$fields['resume_fields']['language_proficiency'] = array(

	    'label' => __( 'Language Proficiency', 'job_manager' ),

	    'type' => 'select',

		'options' =>array('' =>'--Select--','English' => 'English', 'Bangla ' => 'Bangla', 'Hindi' => 'Hindi'),

	    'required' => false,

	    'placeholder' => '',

	    'priority' => 12

	);

	$fields['resume_fields']['reference'] = array(

	    'label' => __( 'Reference', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => 'Reference',

	    'priority' => 12

	); */

	//education

	/* $fields['resume_fields']['candidate_education']['fields']['location'] = array(

	    'label' => __( 'Institute Name', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => '',

	    'priority' => ''

	);

	$fields['resume_fields']['candidate_education']['fields']['qualification'] = array(

	    'label' => __( 'Exam Title', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => '',

	    'priority' => ''

	); */

	/* $fields['resume_fields']['candidate_education']['fields']['date'] = array(

	    'label' => __( 'Start Date', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => 'mm-dd-yy',

	    'priority' => ''

	);

	$fields['resume_fields']['candidate_education']['fields']['end_date'] = array(

	    'label' => __( 'End Eate', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => 'mm-dd-yy',

	    'priority' => ''

	);

	$fields['resume_fields']['candidate_education']['fields']['notes'] = array(

	    'label' => __( 'Notes', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => '',

	    'priority' => ''

	); */

	

	//experience

	 /*  $fields['resume_fields']['candidate_experience']['fields']['employer'] = array(

	    'label' => __( 'Employer', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => '',

	    'priority' => ''

	); */ 

	/* $fields['resume_fields']['candidate_experience']['fields']['job_title'] = array(

	    'label' => __( 'Job Title', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => '',

	    'priority' => 2

	); */ 

	/* $fields['resume_fields']['candidate_experience']['fields']['Dob'] = array(

	    'label' => __( 'Dob', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => 'mm-dd-yy',

	    'priority' => ''

	); 



	  $fields['resume_fields']['candidate_experience']['fields']['date'] = array(

	    'label' => __( 'Start date', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => 'mm-dd-yy',

	    'priority' => ''

	);

	$fields['resume_fields']['candidate_experience']['fields']['end_date'] = array(

	    'label' => __( 'End date', 'job_manager' ),

	    'type' => 'text',

	    'required' => true,

	    'placeholder' => 'mm-dd-yy',

	    'priority' => ''

	);

	$fields['resume_fields']['candidate_experience']['fields']['notes'] = array(

	    'label' => __( 'Notes', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => '',

	    'priority' => ''

	); */

	return $fields; 

}





// Add field to admin

add_filter( 'resume_manager_resume_fields', 'wpjms_admin_resume_form_fields' );

function wpjms_admin_resume_form_fields( $fields ) {	

	$fields['_expected_salary'] = array(

	    'label' => __( 'Expected Salary', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => '',

	    'priority' => 1

	);

	$fields['_training'] = array(

	    'label' => __( 'Training', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => '',

	    'priority' => 1

	);

	$fields['_language_proficiency'] = array(

	    'label' => __( 'Language Proficiency', 'job_manager' ),

	    'type' => 'select',

		'options' =>array('' =>'--Select--','English' => 'English', 'Bangla ' => 'Bangla', 'Hindi' => 'Hindi'),

	    'required' => false,

	    'placeholder' => '',

	    'priority' => 1

	);

	$fields['_reference'] = array(

	    'label' => __( 'Reference', 'job_manager' ),

	    'type' => 'text',

	    'required' => false,

	    'placeholder' => 'Reference',

	    'priority' => 1

	);

	$fields['_key_word_skill']  = array(

                    'label'         => __('Key Ward(e.g. Preferred job listings)', 'job_manager' ),				   

					'type'          => 'select',

					'attr'			=> array('multiple'=>'multiple'),					

					'required'      => false,

					'priority'      => 1,

                    'options'       => getKeyWard(),					

				);

	return $fields;

	

}



// Add your own function to filter the fields

add_filter( 'submit_resume_form_fields', 'custom_submit_resume_form_fields' );



// This is your function which takes the fields, modifies them, and returns them

function custom_submit_resume_form_fields( $fields ) {

    // Here we target one of the job fields (candidate name) and change it's label

    $fields['resume_fields']['candidate_title']['required'] = false;

    // And return the modified fields

    return $fields;

}

add_filter( 'resume_manager_resume_education_fields', 'wpjms_admin_resume_education_fields_remove' );

function wpjms_admin_resume_education_fields_remove( $fields ) {	

	unset( $fields['location'] );

	unset( $fields['qualification'] );

	unset( $fields['date'] );

	unset( $fields['notes'] );	

	return $fields;	

}

add_filter( 'resume_manager_resume_experience_fields', 'wpjms_admin_resume_experience_fields_remove' );

function wpjms_admin_resume_experience_fields_remove( $fields ) {	

	unset( $fields['employer'] );

	unset( $fields['job_title'] );

	unset( $fields['date'] );

	unset( $fields['notes'] );	

	return $fields;

	

}

// Add field to admin

add_filter( 'resume_manager_resume_education_fields', 'wpjms_admin_resume_education_fields' );

function wpjms_admin_resume_education_fields( $fields ) {	

	$fields['location'] = array(

	    'label' 		=> __( 'School name', 'job_manager' ),

	    'name'        => 'resume_education_location[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> '',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['qualification'] = array(

	    'label' 		=> __( 'Qualification(s)', 'job_manager' ),

	    'name'        => 'resume_education_qualification[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> '',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['date'] = array(

	    'label' 		=> __( 'Start date', 'job_manager' ),

	    'name'        => 'resume_education_date[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> 'mm-dd-yy',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['end_date'] = array(

	    'label' 		=> __( 'End date', 'job_manager' ),

	    'name'        => 'resume_education_end_date[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> 'mm-dd-yy',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['notes'] = array(

	    'label' 		=> __( 'Notes', 'job_manager' ),

	    'name'        => 'resume_education_notes[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> '',

	    'description'	=> '',

	    'priority' => 1

	);

	return $fields;	

}



// Add field to admin

add_filter( 'resume_manager_resume_experience_fields', 'wpjms_admin_resume_experience_fields' );

function wpjms_admin_resume_experience_fields( $fields ) {	

	 $fields['employer'] = array(

	    'label' 		=> __( 'Employer', 'job_manager' ),

	    'name'        => 'resume_experience_employer[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> '',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['job_title'] = array(

	    'label' 		=> __( 'Job title', 'job_manager' ),

	    'name'        => 'resume_experience_job_title[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> '',

	    'description'	=> '',

	    'priority' => 1

	); 

	

	$fields['Dob'] = array(

	    'label' 		=> __( 'Dob', 'job_manager' ),

	    'name'        => 'dob[]',

	    'type' 			=> 'date',

	    'placeholder' 	=> '',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['date'] = array(

	    'label' 		=> __( 'Start date', 'job_manager' ),

	    'name'        => 'resume_experience_date[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> 'mm-dd-yy',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['end_date'] = array(

	    'label' 		=> __( 'End date', 'job_manager' ),

	    'name'        => 'resume_experience_end_date[]',

	    'type' 			=> 'text',

	    'placeholder' 	=> 'mm-dd-yy',

	    'description'	=> '',

	    'priority' => 1

	);

	$fields['notes'] = array(

	    'label' 		=> __( 'Notes', 'job_manager' ),

	    'name'        => 'resume_experience_notes[]',

	    'type' 			=> 'textarea',

	    'placeholder' 	=> '',

	    'description'	=> '',

	    'priority' => 1

	);

	return $fields;	

}



add_action('single_job_listing_end', "show_right_side_single_job_listing_end");

function show_right_side_single_job_listing_end(){

	global $post;	  

	  $educational_req = get_post_meta( $post->ID, '_educational_req', true );

	  $experience_req = get_post_meta( $post->ID, '_experience_req', true );

	  $other_req = get_post_meta( $post->ID, '_other', true ); 

	  $procedure_req = get_post_meta( $post->ID, '_procedure', true ); 

	  $uploadff = get_post_meta($post->ID, '_upload', true );  

	  $company_type = get_post_meta( $post->ID, '_company_type', true );

	  $source = get_post_meta($post->ID, '_source', TRUE); 	  

	if ( $educational_req && $company_typ!='govt') {

    echo '<div>

			<h3>' . __( 'Educational Requirements:','workscout' ) .'</h3><br>

			<span>'.$educational_req. '</span>

		</div>';

  } 

  

  if ( $experience_req && $company_typ!='govt') {

    echo '<div>

			<h3>' . __( 'Experience Requirements:','workscout' ) .'</h3><br>

			<span>'.$experience_req. '</span>

		</div>';

	

  }

  

  if ($other_req && $company_typ!='govt') {

    echo '<div>

			<h3>' . __( 'Other Recruitments :','workscout' ) .'</h3><br>

			<span>'.esc_html( $other_req ). '</span>

		</div>';

  }

   if (!empty($uploadff) && $company_type =='govt') {

     $url = esc_url( '' . $uploadff );	

      if ( '' !== $url ) {

       $display = esc_html( $uploadff );   

	     echo '<div style="margin-bottom:30px;">

		 <div class="wrper-outers">

			<div class="lef-box">

				<h3>' . __( 'Job Circular Image:','workscout' ) .'</h3>

			</div>

			<div class="right-boxs">

			  <p style="font-size:14px;"><strong style="font-size:20px;">Source : </strong>'.$source.', '. get_the_date( get_option( 'date_format' ), $post->ID ).'</p>

			</div>

			</div>

			<a class="imag-bodersss-cl" href ='.$url.' target="_blank"><img src='.$uploadff.' style="width:100%;"></a>

		 </div>';

      }      

   }  

   if ($procedure_req && $company_typ!='govt') {
		// modify by Jfrost
		$procedure_req = str_replace("<strong>","<strong style='text-align:center;'>",$procedure_req );
		$procedure_req = str_replace("</strong>","</strong><p></p>",$procedure_req );
    echo '<div class="application-pro">

			<h3>' . __( 'Application Procedure  :','workscout' ) .'</h3>
			
			<div>'. $procedure_req . '</div>

		</div>';

  }

 }

 

/*function wpse_131562_redirect() {

    if ( is_user_logged_in() && is_account_page() && $_SESSION['userRegister'] =='register') {

        // feel free to customize the following line to suit your needs

       wp_logout();

	   wp_redirect(site_url('success-registration'));

        exit;

    }

}

add_action('template_redirect', 'wpse_131562_redirect'); */

 // After registration, logout the user and redirect to home page

//function custom_registration_redirect() {

    //wp_logout();	

	//session_start();

	//$_SESSION['userRegister'] = 'register';

	//wp_redirect(site_url('success-registration'));

	//exit();

//}

//add_action('woocommerce_registration_redirect', 'custom_registration_redirect', 2);



add_action( 'wp_logout', 'auto_redirect_external_after_logout');



function auto_redirect_external_after_logout(){

	wp_redirect(home_url());

	exit();

}



// Add term and conditions check box on registration form

add_action( 'woocommerce_register_form', 'add_terms_and_conditions_to_registration', 20 );

function add_terms_and_conditions_to_registration() {	

    if ( wc_get_page_id( 'terms' ) > 0 ) {

        ?>

        <p class="form-row terms wc-terms-and-conditions">

            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">

                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" /> <span><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link">terms &amp; conditions</a>', 'woocommerce' ), esc_url( wc_get_page_permalink( 'terms' ) ) ); ?></span> <span class="required">*</span>

            </label>

            <input type="hidden" name="terms-field" value="1" />

        </p>

    <?php

    }

}



// Validate required term and conditions check box

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

	if ( isset( $_POST['password_again'] ) && empty( $_POST['password_again'] ) ) {

        $validation_errors->add( 'password_again_error', __( '<strong>Error</strong>: Please enter confirm password!', 'woocommerce' ) );

    }

	if ( $_POST['password']  != $_POST['password_again'] ) {

        $validation_errors->add( 'password_again_error', __( '<strong>Error</strong>: The password and confirmation password do not match.', 'woocommerce' ) );

    }

	if ( isset( $_POST['role'] ) && empty( $_POST['role'] ) ) {

        $validation_errors->add( 'role_error', __( '<strong>Error</strong>: You must select "I want to register as"', 'woocommerce' ) );

    }

	

	if ( ! isset( $_POST['terms'] ) ){

        $validation_errors->add( 'terms_error', __( 'Terms and condition are not checked!', 'woocommerce' ) );

	}

	

	return $validation_errors;

}



function wooc_save_extra_register_fields( $user_id ) {

	if(isset($_POST['role'])){

   		wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['role'] ) );

   	}

    if ( isset( $_POST['terms'] ) ) {

        // Phone input filed which is used in WooCommerce

         update_user_meta( $user_id, 'terms', $_POST['terms'] );

    } 

	if ( 'employer' === $_POST['role'] ) {

           wp_redirect( site_url('my-account/edit-address/billing/') );

		  exit();

        }else if ( 'candidate' === $_POST['role'] ) {

            wp_redirect( site_url('my-account/?q=') );

			exit();

     }

    

}

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );



/**

 * Add login logout menu item in the main menu.

 * ===========================================

 */

add_filter( 'wp_nav_menu_items', 'lunchbox_add_loginout_link', 5, 2 );

function lunchbox_add_loginout_link( $items, $args ) {

    /**

     * If menu primary menu is set & user is logged in.

     */

	$user = wp_get_current_user();

    if( $args->theme_location == 'primary' ){

		$url ='';

	//if( in_array( 'employer', (array) $user->roles ) && !empty( get_user_meta( get_current_user_id(), 'user_selected_package_id', true ) )) {

		//$url ='<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('post-a-job').'">Post a Job</a></li>';

	//}else{

		if(is_user_logged_in()){

			$url ='<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('package').'">Post a Job</a></li>';

			}else{

			//$url = '<li><a href="#login-dialog" class="small-dialog popup-with-zoom-anim">Post a free Job</a></li>';

		}

	//}

	 if ( !in_array( 'candidate', (array) $user->roles ) || !is_user_logged_in() ){

			$class1="";

			if( is_archive('resumes') ){

				$class1="current-menu-parent";

			}

			if( is_page(array('package','report-a-problem')) ){   

				$class1="current-menu-parent";

			}		
			if( is_page(array('package','faq')) ){   

				$class1="current-menu-parent";

			}			
	

			$items .='<li style="display:none;" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children '.$class1.'">

			<a class="" style="display:none;">Employers</a>

			<ul class="sub-menu mobile-display" style="display: none;">

				<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('job-dashboard').'">Employers Account</a></li>

				<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('package').'">Post a Free Job</a></li>

				<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('contact-us').'">Report a Problem</a></li>

				<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('faq').'">	FAQ</a></li>

				</ul>

			</li>';

			

			/*

			<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('resumes').'">Browse Candidates</a></li>

				<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('employers-center').'">Employers Center</a></li>

				'.$url.'				

				<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Employers</a></li>

				<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'.site_url('report-a-problem').'">Report a Problem</a></li>

				*/

	} 

	if ( is_user_logged_in() ) {

		if ( in_array( 'employer', (array) $user->roles ) ) {

			$class="";

			

			if( is_page(array('package','post-a-job')) ){

				$class="current-menu-item current_page_item";

			}

			//if(!empty( get_user_meta( get_current_user_id(), 'user_selected_package_id', true ) )){

			//$items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page '.$class.'"><a href="'.site_url('post-a-job').'">Post a Job</a></li>';

			//}else{

			$items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page '.$class.'"><a href="'.site_url('package').'">Post a Free jobs</a></li>';

			//}

		} 

	 } else {

		 $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page "><a href="'.site_url('package').'">Post a Free jobs</a></li>';

	 }

	if ( in_array( 'candidate', (array) $user->roles ) ){

		$items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page "><a href="'.site_url('signup-as-employer-message').'">Post a Free jobs</a></li>';

	}

	 

	}

    return $items;

}



//Get job listing category.

function getKeyWard(){

	global $wpdb;

	$dataArray =array();

	$sql="SELECT {$wpdb->prefix}terms.term_id,{$wpdb->prefix}terms.name

 FROM {$wpdb->prefix}term_taxonomy

 INNER JOIN {$wpdb->prefix}terms on {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_taxonomy_id

  WHERE taxonomy = 'job_listing_category'";

	$results = $wpdb->get_results($sql);	

	if(!empty($results)){

		foreach( $results as $val ){

		   $dataArray[$val->term_id]=$val->name;

		}

		return $dataArray;

	}else{

	  return false;

	}

}

// get term category name

function getCatName( $termsArray ){

	global $wpdb;

	if( !empty( $termsArray ) ){

	$term = implode(',',$termsArray );

	

	$sql="SELECT GROUP_CONCAT({$wpdb->prefix}terms.name) as name

 FROM {$wpdb->prefix}term_taxonomy

 INNER JOIN {$wpdb->prefix}terms on {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_taxonomy_id

  WHERE taxonomy = 'job_listing_category' AND {$wpdb->prefix}terms.term_id IN(".$term." )";

	$results = $wpdb->get_results($sql);	

	if(!empty($results)){

		return $results[0]->name;

	}

	}else{

	  return false;

	}

}



function my_woocommerce_add_error( $error ) {

    return str_replace('An account is already registered with your email address. Please log in','That email address is already in use. If you already have an account, please sign in',$error);    

}

add_filter( 'woocommerce_add_error', 'my_woocommerce_add_error' );



function no_wordpress_errors(){

  return 'The username and password you specified are invalid. Please try again';

}

add_filter( 'login_errors', 'no_wordpress_errors' );



//Remove fields

add_filter( 'woocommerce_billing_fields', 'wc_optional_billing_fields', 10, 1 );

function wc_optional_billing_fields( $fields ) {

	//echo "<pre>";print_r($fields);

	unset( $fields['billing_company']);

	unset( $fields['billing_address_2']);	

	$fields['billing_postcode']['required'] =false;

	$fields['billing_postcode']['label'] ='Post Code';

	$fields['billing_phone']['required'] =false;

	$fields['billing_email']['required'] =false;	

    return $fields;

}



//function my_login_redirect( $redirect_to) {

    //is there a user to check?

	//$user = wp_get_current_user(); 

	//print_r($user);  

		/*if ( in_array( 'candidate', (array) $user->roles ) ){

			header("Location: https://amarbdjobs.com/my-account");

			exit();

		}

		else if ( in_array( 'employer', (array) $user->roles ) ){

			header("Location: https://amarbdjobs.com/my-account/edit-address/billing");

			//exit();

		}*/

		//return home_url( '/my-account' );

		 

//}

//add_filter( 'woocommerce_login_redirect', 'my_login_redirect', 10, 3 );

function wpse_19692_registration_redirect() {

    return home_url( '/my-account/?q=' );

}

add_filter( 'registration_redirect', 'wpse_19692_registration_redirect' );



function my_login_redirect( $redirect_to, $request, $user ) {

    //is there a user to check?

    if ( isset( $user->roles ) && is_array( $user->roles ) ) {

        //check for admins

        if ( in_array( 'administrator', $user->roles ) ) {

            // redirect them to the default place

            return $redirect_to;

        } else if ( in_array( 'candidate', $user->roles ) ) {

            return home_url( '/candidate-dashboard/?ss=' );

        }

    } else {

        return $redirect_to;

    }

}

 

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );



function wc_registration_redirect( $redirect_to ) {     // prevents the user from logging in automatically after registering their account

    wp_logout();

    wp_redirect( '/my-account/?q=');                        // redirects to a confirmation message

    exit;

}



function wp_authenticate_user( $userdata ) {            // when the user logs in, checks whether their email is verified

    $has_activation_status = get_user_meta($userdata->ID, 'is_activated', false);

    if ($has_activation_status) {                           // checks if this is an older account without activation status; skips the rest of the function if it is

        $isActivated = get_user_meta($userdata->ID, 'is_activated', true);

        if ( !$isActivated ) {

            my_user_register( $userdata->ID );              // resends the activation mail if the account is not activated

            $userdata = new WP_Error(

                'my_theme_confirmation_error',

                __( '<strong>Error:</strong> Your account has to be activated before you can login. Please click the link in the activation email that has been sent to you.<br /> If you do not receive the activation email within a few minutes, check your spam folder or <a href="/my-account/?u='.$userdata->ID.'">click here to resend it</a>.' )

            );

        }

    }

    return $userdata;

}



function my_user_register($user_id) {               // when a user registers, sends them an email to verify their account

    $user_info = get_userdata($user_id);                                            // gets user data

    $code = md5(time());                                                            // creates md5 code to verify later

    $string = array('id'=>$user_id, 'code'=>$code);   

	$user = get_user_by( 'id', $user_id ); 

	if( $user ) {

		wp_set_current_user( $user_id, $user->user_login );

		wp_set_auth_cookie( $user_id );

		do_action( 'wp_login', $user->user_login, $user );

	}

	// makes it into a code to send it to user via email

    update_user_meta($user_id, 'is_activated', 0);                                  // creates activation code and activation status in the database

    update_user_meta($user_id, 'activationcode', $code);

    $url = get_site_url(). '/my-account/?p=' .base64_encode( serialize($string));       // creates the activation url

    $html = ( '<html>

<head>

 <style>

	@media only screen and (max-width: 620px) {

	  table[class=body] h1 {

		font-size: 28px !important;

		margin-bottom: 10px !important;

	  }

	  table[class=body] p,

			table[class=body] ul,

			table[class=body] ol,

			table[class=body] td,

			table[class=body] span,

			table[class=body] a {

		font-size: 16px !important;

	  }

	  table[class=body] .wrapper,

			table[class=body] .article {

		padding: 10px !important;

	  }

	  table[class=body] .content {

		padding: 0 !important;

	  }

	  table[class=body] .container {

		padding: 0 !important;

		width: 100% !important;

	  }

	  table[class=body] .main {

		border-left-width: 0 !important;

		border-radius: 0 !important;

		border-right-width: 0 !important;

	  }

	  table[class=body] .btn table {

		width: 100% !important;

	  }

	  table[class=body] .btn a {

		width: 100% !important;

	  }

	  table[class=body] .img-responsive {

		height: auto !important;

		max-width: 100% !important;

		width: auto !important;

	  }

	}

	@media all {

	  .ExternalClass {

		width: 100%;

	  }

	  .ExternalClass,

			.ExternalClass p,

			.ExternalClass span,

			.ExternalClass font,

			.ExternalClass td,

			.ExternalClass div {

		line-height: 100%;

	  }

	  .apple-link a {

		color: inherit !important;

		font-family: inherit !important;

		font-size: inherit !important;

		font-weight: inherit !important;

		line-height: inherit !important;

		text-decoration: none !important;

	  }

	  .btn-primary table td:hover {

		background-color: #34495e !important;

	  }

	  .btn-primary a:hover {

		background-color: #34495e !important;

		border-color: #34495e !important;

	  }

	}

	</style>

  </head>

  <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">

	<table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">

	  <tr>

		<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>

		<td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">

		  <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

			<span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>

			<table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

			<tr>

				<td class="wrapper" style="    padding: 0 40px; font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">

				  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">

					<tr>

					  <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">

					  <div align="center"><h1 style="font-family: sans-serif; font-size: 45px; font-weight: bold; margin: 0; Margin-bottom: 15px; text-align: center; color: green; text-shadow: 0 1px 0 green;">FreeBdjobs</h1></div>

						<h1 style="font-family: sans-serif; font-size: 25px; font-weight: bold; margin: 0; Margin-bottom: 15px; text-align: center; color: #000; text-shadow: 0 1px 0 #000000;">Thanks for joining FreeBdjobs</h1>

						<p style="font-family: sans-serif; font-size: 19px; font-weight: normal; margin: 0; Margin-bottom: 15px; text-align: center;">Activate your FreeBdjobs account by clicking the Button bellow</p>

						<p style="font-family: sans-serif; font-size: 19px; font-weight: normal; margin: 0; Margin-bottom: 15px; text-align: center;">By creatinf an account, | agree to FreeBdjobs <br/><a style="color:#15c;" href="'. get_bloginfo('url') .'/terms-of-use/" target="_blank">Terms of Use</a> and <a style="color:#15c;" href="'. get_bloginfo('url') .'/privacy-policy/" target="_blank">Privacy Policy</a></p>

						<p style="font-family: sans-serif; color: #ffffff; font-weight: normal; text-decoration: none; font-weight: normal; margin: 0; Margin-bottom: 15px; text-align: center;"><a target="_blank" style="color: #ffffff; font-weight: normal; text-decoration: none; background: green;padding: 8px 25px; display: inline-block; font-size: 25px; border-radius: 3px;

" href="'.$url.'">Activate Account</a></p>

						<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px; text-align: center;">if you encounter any issues, please <a href="mailto:accounts@freebdjobs.com" style="color:#15c;">visit our help center</a></p>

					   </td>

					</tr>

				  </table>

				</td>

			  </tr>

			</table> 

		  </div>

		</td>

		<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>

	  </tr>

	</table>

  </body>

</html>'	); 

// This is the html template for your email message body

    wc_mail($user_info->user_email, __( 'Activate your Account' ), $html);  

	// sends the email to the user

}



function my_init(){                                 // handles all this verification stuff

    if(isset($_GET['p'])){                                                  // If accessed via an authentification link

        $data = unserialize(base64_decode($_GET['p']));

        $code = get_user_meta($data['id'], 'activationcode', true);

        $isActivated = get_user_meta($data['id'], 'is_activated', true);    // checks if the account has already been activated. We're doing this to prevent someone from logging in with an outdated confirmation link

        if( $isActivated ) {                                                // generates an error message if the account was already active

            wc_add_notice( __( 'This account has already been activated. Please log in with your username and password.' ), 'error' );

        }

        else {

            if($code == $data['code']){                                     // checks whether the decoded code given is the same as the one in the data base

                update_user_meta($data['id'], 'is_activated', 1);           // updates the database upon successful activation

                $user_id = $data['id'];                                     // logs the user in

                $user = get_user_by( 'id', $user_id ); 

                if( $user ) {

                    wp_set_current_user( $user_id, $user->user_login );

                    wp_set_auth_cookie( $user_id );

                    do_action( 'wp_login', $user->user_login, $user );



					

                }

                wc_add_notice( __( '<strong>Success:</strong> Your account has been activated! You have been logged in and can now use the site to its full extent.' ), 'notice' );

            } else {

                wc_add_notice( __( '<strong>Error:</strong> Account activation failed. Please try again in a few minutes or <a href="/my-account/?u='.$userdata->ID.'">resend the activation email</a>.<br />Please note that any activation links previously sent lose their validity as soon as a new activation email gets sent.<br />If the verification fails repeatedly, please contact our administrator.' ), 'error' );

            }

        }

    }

    if(isset($_GET['u'])){                                

	// If resending confirmation mail

        my_user_register($_GET['u']);

        wc_add_notice( __( 'Your activation email has been resent. Please check your email and your spam folder.' ), 'notice' );

    }

    if(isset($_GET['n'])){                                          

	// If account has been freshly created

        wc_add_notice( __( 'Thank you for creating your account. You will need to confirm your email address in order to activate your account. An email containing the activation link has been sent to your email address. If the email does not arrive within a few minutes, check your spam folder.' ), 'notice' );

    }

	if(isset($_GET['q'])){

		wc_add_notice( __( 'Success: Thank you for your registration. Please check your email to activate account! ', 'inkfool' ) );

		}

		if ( is_user_logged_in() ) {

			$current_user = wp_get_current_user();

				 if( current_user_can('editor') || current_user_can('administrator') ) {  

			

				 } else {

					$activationcode = get_user_meta($current_user->ID, 'is_activated', true);

					if($activationcode == 0 ){

						wc_add_notice( __( 'Confirm Your Email Account' ) );

						} else {

							if(isset($_GET['ss'])){

								wc_add_notice( __( 'Thank You. Your account has been activated!', 'inkfool' ) );

							}

						}

				 }

		}

		

}



// the hooks to make it all work

add_action( 'init', 'my_init' );

add_filter('woocommerce_registration_redirect', 'wc_registration_redirect');

add_filter('wp_authenticate_user', 'wp_authenticate_user',10,2);

add_action('user_register', 'my_user_register',10,2);



function action_woocommerce_customer_save_address( $user_id, $load_address ) { 

$current_user = wp_get_current_user();

$roles = $current_user->roles[0];

if($roles == 'employer'){

	wp_safe_redirect( get_site_url().'/job-dashboard/?suss=');

} else if($roles == 'candidate') {

		wp_safe_redirect( get_site_url().'/candidate-dashboard/?suss=');

}

	exit;

}; 

add_action( 'woocommerce_customer_save_address', 'action_woocommerce_customer_save_address', 99, 2 ); 



/* add_filter( 'submit_resume_steps', 'replace_resume_done_with_redirect' );



function replace_resume_done_with_redirect( $steps ) {

    $steps['preview'] = array(

        'priority' => 30,

        'handler' => function() {

		do_action( 'resume_manager_resume_submitted', WP_Resume_Manager_Form_Submit_Resume::instance()->get_resume_id() );

		$job_id = WP_Resume_Manager_Form_Submit_Resume::instance()->get_job_id();



		// Allow application

		if ( ! empty( $job_id ) ) {

			echo '<h3 class="applying_for">' . sprintf( __( 'Submit your application to the job "%s".', 'wp-job-manager-resumes' ), '<a href="' . get_permalink( $job_id ) . '">' . get_the_title( $job_id ) . '</a>' ) .'</h3>';

			echo do_shortcode( '[job_apply id="' . absint( $job_id ) . '"]' );

		} else {

			$dashboard_id = get_option( 'resume_manager_candidate_dashboard_page_id' );

			if ( ! empty( $dashboard_id ) && wp_redirect( get_permalink( $dashboard_id ) ) ) {

				exit;

			}

		}

        },

        'view' => null,

    );

    return $steps;

} */

function hook_javascript() {

    ?>

        <script>

          jQuery(function($){

				jQuery('.matchheight').matchHeight();

				setTimeout(function(){ 

				    jQuery('a.reset').text('Refresh');

				  }, 3000);

				   jQuery('a.reset').text('Refresh');

			});

			jQuery( document ).ready(function() {

			 

			jQuery('#online-resume').on('change', function() {

			var selectedText =jQuery(this).find("option:selected").text();

			 if(selectedText == 'Choose an online resume...'){

				jQuery('.fieldset-upload-cv').show();

			 } else {

				jQuery('.fieldset-upload-cv').hide();

			 }

			});

			});

        </script>

    <?php

}

add_action('wp_head', 'hook_javascript');

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {

  echo '<style>

	.tablenav select[name=job_listing_filled],

	.tablenav select[name=job_listing_featured]{

		display:none;

	}

	td.job_tags, th#job_tags {

     display: none;

    }

     td.job_listing_type, th#job_listing_type, th#job_expires_or_closing_date, td.job_expires_or_closing_date {

        display: none;

    }

    th.manage-column.column-job_listing_type, th.manage-column.column-job_tags, th.manage-column.column-job_expires_or_closing_date {

        display: none;

    }

  </style>';

}







function wisdom_filter_tracked_plugins() {

  global $typenow;

  global $wp_query;

    if ( $typenow == 'job_listing' ) { // Your custom post type slug

      $current_plugin = '';

      if( isset( $_GET['slug'] ) ) {

        $current_plugin = $_GET['slug']; // Check if option has been selected

      }

	  if( isset( $_GET['comny_type'] ) ) {

			$comny_type = $_GET['comny_type']; // Check if option has been selected

      } 

	  ?>

      <select name="slug" id="slug">

        <option value="all" <?php selected( 'all', $current_plugin ); ?>><?php _e( 'Job Posting Type', 'wisdom-plugin' ); ?></option>

          <option value="paid " <?php selected( 'paid', $current_plugin ); ?>>Premium Posting Type </option>

          <option value="free" <?php selected( 'free', $current_plugin ); ?>>Free Posting Type</option>

      </select>

	   <select name="comny_type" id="comny_type">

        <option value="all" <?php selected( 'all', $comny_type ); ?>><?php _e( 'Company Type', 'wisdom-plugin' ); ?></option>

          <option value="private" <?php selected( 'private', $comny_type ); ?>>Private Company</option>

          <option value="govt" <?php selected( 'govt', $comny_type ); ?>>Government Company</option>

      </select>

  <?php }

}

add_action( 'restrict_manage_posts', 'wisdom_filter_tracked_plugins' );



function wisdom_sort_plugins_by_slug( $query ) {

  global $pagenow;

  

  // Get the post type

  $post_types = $_GET['post_type'];

   // echo 'dlug->'.$post_types;

  if ( is_admin() && $pagenow=='edit.php' && $post_types == 'job_listing' && isset( $_GET['slug'] ) && $_GET['slug'] !='all' ) {



    $query->query_vars['meta_key'] = 'package_plan_type';

    $query->query_vars['meta_value'] = $_GET['slug'];

    $query->query_vars['meta_compare'] = '=';

  }

   if ( is_admin() && $pagenow=='edit.php' && $post_types == 'job_listing' && isset( $_GET['comny_type'] ) && $_GET['comny_type'] !='all' ) {

    $query->query_vars['meta_key'] = '_company_type';

    $query->query_vars['meta_value'] = $_GET['comny_type'];

    $query->query_vars['meta_compare'] = '=';

  }

}

add_filter( 'parse_query', 'wisdom_sort_plugins_by_slug' );



function yoast_seo_remove_columns( $columns ) {

	/* remove the Yoast SEO columns */

	unset( $columns['job_listing_filled'] );

	

	return $columns;

}



/* remove from posts */

add_filter ( 'manage_edit-post_columns', 'yoast_seo_remove_columns' );



/* ajax url */

add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {

	echo '<script async type="text/javascript">

   var ajaxurl = "' . admin_url('admin-ajax.php') . '";

 </script>';

}



add_action( 'wp_ajax_user_updateone', 'ajax_user_updateone' );

add_action('wp_ajax_nopriv_user_updateone','ajax_user_updateone');

function ajax_user_updateone(){

$current_user = wp_get_current_user();

 if ( is_user_logged_in() ) {

	update_user_meta($current_user->ID, 'update_onetime_usermeta', 1 );

 }



}