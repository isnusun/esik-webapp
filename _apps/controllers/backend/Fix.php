<?php
/*
 * Jsphp.php
 * 
 * Copyright 2016 Isnu Suntoro <isnusun@isnusun-X450LCP>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Fix extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('siteman_model');
	}

	public function fix_art(){
		$strSQL = "UPDATE bdt_idv i SET i.percentile=(SELECT percentile from bdt_rts r WHERE r.periode_id=1 AND r.idbdt=i.idbdt LIMIT 1) WHERE i.periode_id=1 and i.percentile=0 LIMIT 2000;";
		if($this->db->query($strSQL)){
			echo $strSQL . "\n";
			sleep(10);
		}
		$strSQL = "UPDATE bdt_idv i SET i.percentile=(SELECT percentile from bdt_rts r WHERE r.periode_id=1 AND r.idbdt=i.idbdt LIMIT 1) WHERE i.periode_id=1 and i.percentile=0 LIMIT 2000;";
		if ($this->db->query($strSQL)) {
			echo $strSQL . "\n";
			sleep(2);
		}
	}
}