<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Form extends CI_Model
{
	public function inputText($id = NULL, $label = NULL, $value = NULL, $attrib = NULL)
	{
		$form[] = '
		<div class="form-group">
           <label for="' . $id . '">' . $label . '</label>
           <input type="text"  class="form-control" value="' . $value . '" id="' . $id . '" name="' . $id . '" ' . $attrib . '>
        </div>';
		return $form;
	}
	public function inputCheckbox($id = NULL, $label = NULL, $value = NULL, $attrib = NULL)
	{
		$checked = ($value == 1 || $value == 'true') ? 'checked' : '';
		$form[] = '
			<div class="form-group">
				<div class="form-check">
					<input type="hidden" name="' . $id . '" value="0">
					<input type="checkbox" id="' . $id . '" name="' . $id . '" class="form-check-input" value="1" ' . $checked . ' ' . $attrib . '>
					<label class="form-check-label" for="' . $id . '">' . $label . '</label>
				</div>
			</div>';
		return $form;
	}

	public function inputPassword($id = NULL, $label = NULL, $value = NULL, $attrib = NULL)
	{
		$form[] = '
		<div class="form-group">
           <label for="' . $id . '">' . $label . '</label>
           <input type="password"  class="form-control" value="' . $value . '" id="' . $id . '" name="' . $id . '" ' . $attrib . '>
        </div>';
		return $form;
	}
	public function inputEnumOptions($name, $label, $enum_values, $selected_value = '', $attributes = '')
	{
		$options = '<div class="form-group">';
		$options .= '<label for="' . $name . '">' . $label . '</label>';
		$options .= '<select id="' . $name . '" name="' . $name . '" class="form-control" ' . $attributes . '>';

		foreach ($enum_values as $value) {
			$selected = ($value == $selected_value) ? 'selected' : '';
			$options .= '<option value="' . $value . '" ' . $selected . '>' . $value . '</option>';
		}

		$options .= '</select>';
		$options .= '</div>';

		return $options;
	}

	public function inputTextRow($id = NULL, $label = NULL, $value = NULL, $attrib = NULL)
	{
		$form[] = '
				<div class="form-group row mt-4">
				<div class="col-sm-3">
                                 <label for="' . $id . '">' . $label . '</label>
								 </div>
								 <div class="col-sm-9">
          							 <input type="text"  class="form-control" value="' . $value . '" id="' . $id . '" name="' . $id . '" ' . $attrib . '>

								 </div>
                     
         			</div>
					
					';
		return $form;
	}


	public function selectText($id = NULL, $label = NULL, $table, $kolom, $value = NULL, $attrib = NULL, $where = NULL)
	{
		$this->db->select($kolom);
		if ($where != NULL) {
			$this->db->where($where);
		}
		$query = $this->db->get($table);
		$options = $query->result();
		$selectedValue = !empty($value) ? 'value="' . $value . '"' : '';
		$form[] = '<div class="form-group">
				   <label for="' . $id . '">' . $label . '</label>
				   <select class="form-control" name="' . $id . '" id="' . $id . '" ' . $attrib . '>';
		foreach ($options as $option) {
			$selected = ($value == $option->$kolom) ? 'selected' : '';
			$form[] = '<option value="' . $option->$kolom . '" ' . $selected . '>' . $option->$kolom . '</option>';
		}
		$form[] = '</select>
				  </div>';
		return $form;
	}

	public function getKec($id_kecamatan = NULL, $label_kecamatan = NULL, $value_kecamatan = NULL, $class = NULL, $attrib_kecamatan = NULL)
	{
		$this->load->model('backend/Location');
		$form = [];
		$form[] = '<div class="' . $class . '">
					<div class="form-group">
					  <label for="' . $id_kecamatan . '">' . $label_kecamatan . '</label>
					  <select class="form-control" name="' . $id_kecamatan . '" id="' . $id_kecamatan . '" ' . $attrib_kecamatan . '>
						  <option value="">Pilih Kecamatan</option>';

		$kecamatan = $this->Location->get_kecamatan();
		foreach ($kecamatan as $kec) {
			$selected = ($value_kecamatan == $kec['id']) ? 'selected' : '';
			$form[] = '<option value="' . $kec['id'] . '" ' . $selected . '>' . $kec['nama'] . '</option>';
		}
		$form[] = '</select></div></div>';

		return implode('', $form);
	}


	public function selectKec($id_kecamatan = NULL, $id_kelurahan = NULL, $label_kecamatan = NULL, $label_kelurahan = NULL, $value_kecamatan = NULL, $value_kelurahan = NULL, $class = NULL, $attrib_kecamatan = NULL, $attrib_kelurahan = NULL)
	{
		$this->load->model('backend/Location');
		$form = [];
		$form[] = '<div class="' . $class . '">
                <div class="form-group">
                  <label for="' . $id_kecamatan . '">' . $label_kecamatan . '</label>
                  <select class="form-control" name="' . $id_kecamatan . '" id="' . $id_kecamatan . '" ' . $attrib_kecamatan . '>
                      <option value="">Pilih Kecamatan</option>';

		$kecamatan = $this->Location->get_kecamatan();
		foreach ($kecamatan as $kec) {
			$selected = ($value_kecamatan == $kec['id']) ? 'selected' : '';
			$form[] = '<option value="' . $kec['id'] . '" ' . $selected . '>' . $kec['nama'] . '</option>';
		}
		$form[] = '</select></div></div>';
		$form[] = '<div class="' . $class . '">
                <div class="form-group">
                  <label for="' . $id_kelurahan . '">' . $label_kelurahan . '</label>
                  <select class="form-control" name="' . $id_kelurahan . '" id="' . $id_kelurahan . '" ' . $attrib_kelurahan . '>
                      <option value="">Pilih Kelurahan</option>';

		if ($value_kelurahan) {
			$kelurahan = $this->Location->get_kelurahan_by_kecamatan($value_kecamatan);
			foreach ($kelurahan as $kel) {
				$selected = ($value_kelurahan == $kel['id']) ? 'selected' : '';
				$form[] = '<option value="' . $kel['id'] . '" ' . $selected . '>' . $kel['nama'] . '</option>';
			}
		}

		$form[] = '</select>
               </div>
               </div>';

		return implode('', $form);
	}

	public function hiddenText($id = NULL, $value = NULL, $attrib = NULL)
	{
		if (!empty($value)) {
			$value = 'value="' . $value . '"';
		}
		$form[] = '<input type="hidden" ' . $value . ' id="' . $id . '" name="' . $id . '" ' . $attrib . '>';
		return $form;
	}
	public function modalKu($abjad, $label, $link, $actions = ['edit', 'delete'])
	{
		$modal[] = '<div class="modal fade bd-example-modal-lg" id="myModal' . $abjad . '" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="editModalLabel">' . $label . ' Modal</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <form action="' . site_url($link) . '" method="post" enctype="multipart/form-data">
		  <div class="modal-body" id="modalku' . $abjad . '">
		  </div>
		  <div class="modal-footer pull-right">';
		$modal[] = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';

		if (in_array('edit', $actions)) {
			$modal[] = '<button type="submit" name="AKSI" value="Edit" class="btn btn-info">Save changes</button>';
		}
		if (in_array('delete', $actions)) {
			$modal[] = '<button type="submit" name="AKSI" value="Delete" class="btn btn-danger">Confirm Delete</button>';
		}
		$modal[] = '
		  </div>
		  </form>
		</div>
	  </div>
	</div>';
		return $modal;
	}
}
