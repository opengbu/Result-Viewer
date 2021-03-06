<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faculty extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('faculty_model');
	}
	
	public function signin()
	{
		if($this->session->userdata('logged')=='faculty')
		{
			redirect('faculty/upload_form');
		}
		else
		{
			if($this->session->userdata('logged')=='admin')
			{
				$this->session->sess_destroy();
			}
			$this->load->library('form_validation');
			$this->load->view('theme/common/link');
			$this->load->view('theme/homepage/header');
			$this->load->view('theme/faculty/signin');
			$this->load->view('theme/common/footer');
		}
		
	}
	
	public function validate_signin()
	{	
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->signin();
		}
		else
		{
			$this->verify_signin();
		}
	}
	
	public function verify_signin()
	{
		$q = $this->faculty_model->faculty_verify();
		if( $q == 0 )
		{
			$this->session->set_flashdata('no_rec', '* Details incorrect !!');	
			redirect('faculty/signin');
		}
		else if( $q == 1 )
		{
			echo "Error : Multiple faculty found !!";
		}
		else if($q == 2)
		{
				redirect('faculty/upload_form');
		}
	}

	
	public function upload_form()
	{
		if($this->session->userdata('logged')=='faculty')
		{
			$data['sch_list'] = $this->faculty_model->school_list();
			$this->load->view('theme/common/link');
			$this->load->view('theme/faculty/header');
			$this->load->view('theme/faculty/upload',$data);
			$this->load->view('theme/common/footer');
		}
		else
		{
			redirect('home');
		}
	}

	public function upload_db()
	{
		if($this->session->userdata('logged')=='faculty')
		{
			$fac_id = $this->faculty_model->faculty_id();
			$upload_status = $this->faculty_model->excel_upload($fac_id);
			if($upload_status==1)
			{
				$this->session->set_flashdata('uploaded', '* Your excel sheet has been successfully uploaded !!');
				redirect('faculty/sheets');
			}
			else if($upload_status==0)
			{
				$this->session->set_flashdata('uploaded', '* Some problem occured. Please upload again !!');
				redirect('faculty/upload_form');
			}
		}
		else
		{
			redirect('home');
		}	
	}
	
	public function sheets()
	{
		if($this->session->userdata('logged')=='faculty')
		{
			$fac_id = $this->faculty_model->faculty_id();
			$data['fac_sht'] = $this->faculty_model->faculty_sheets($fac_id);
			$this->load->view('theme/common/link');
			$this->load->view('theme/faculty/header');
			$this->load->view('theme/faculty/existing_sheets',$data);
			$this->load->view('theme/common/footer');
		}
		else
		{
			redirect('home');
		}

	}
	
	public function logout()
	{
		$action = "Logged-Out";
		$this->logs_model->insert($action);
		$this->session->sess_destroy();
		redirect('home');
	}
}

