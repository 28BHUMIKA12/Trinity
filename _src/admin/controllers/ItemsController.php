<?php

/**
 * Items_ItemsController class.
 * 
 * @extends Zend_Controller_Action
 */
class Admin_ItemsController extends Core_Admin{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){	
		
		parent::init();
		parent::layout('admin');
	}
	
	/**
	 * indexAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function indexAction(){
		$this->_redirect('/admin/users/list');
	}
	
	/**
	 * addAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function addAction(){
	
		$p = $this->params;
		
		$this->view->t = Model_DbTable_Item::GROCERY;
		if($p['t'])
			$this->view->t = $p['t'];
		
		$this->view->data = $this->item->fetchAll("(eat_type='".$this->view->t."' and parent_id='1' ) or id='1'");

		// if posted
		if($this->getRequest()->isPost()){
			
			$check = $this->item->fetchAll("name='".trim($p['name'])."'");
			// if name already exists
			if(count($check)){
				$err = true;
				$this->view->err = 'Name already taken.';
			}
			
			
			if(!$err){
				// check if created fine
				if($LastId = $this->item->doCreate($p)){
					
					foreach($p['meal_id'] as $key=>$value){
						$insert['item_id'] = $LastId;
						$insert['meal_id'] = $value;
						$this->meal_item->doCreate($insert);
					}
					// trim before saving
					$p['name'] = trim($p['name']);
					$this->view->msg = 'Added successfully';
				}
				else{
					$this->view->err = 'Cannot Add. An error occurred.';
				}
			}
		}
	}
	
	/**
	 * editAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function editAction(){
	
		$p = $this->params;
		
		$this->view->itemData = $this->item->doRead($p['id']);

		$this->view->t = $this->view->itemData->eat_type;
		if($p['t'])
			$this->view->t = $p['t'];
		
		$this->view->data = $this->item->fetchAll("(eat_type='".$this->view->t."' and parent_id='1' and id!='".$p['id']."' ) or id='1'");

		// if posted
		if($this->getRequest()->isPost()){
		
			$this->item->doUpdate($p,"id='".$p['id']."'"); 
			
			$this->meal_item->delete("item_id='{$p['id']}'");			
			foreach($p['meal_id'] as $key=>$value){
				$insert['item_id'] = $p['id'];
				$insert['meal_id'] = $value;
				$this->meal_item->doCreate($insert);
			}


			if($p['parent_id'] != '1')
				$this->_redirect('admin/items/list/parent_id/'.$p['parent_id']);
			else
				$this->_redirect('admin/items/list/');
		}
		
		$this->view->stats = $this->item_stats->fetchAll("item_id='{$p['id']}' and is_custom=''");
		$this->view->exclusive = $this->item_exclusive->fetchAll("item_id='{$p['id']}'");
		
		
		
	}
	
	/**
	 * addStatsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function addStatsAction(){
	
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){
			
			$this->item_stats->doCreate($p);
		}
		
		$this->_redirect("/admin/items/edit/id/".$p['item_id']);
	}
	
	/**
	 * addExclusivityAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function addExclusivityAction(){
	
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){		
			
			if($p['hidden-tags']){
				$tags = explode(',',$p['hidden-tags']);
				
				foreach($tags as $key=>$value){
					$insert['item_id'] = $p['item_id'];
					$insert['x_id'] = $value;
					
					$check = $this->item_exclusive->fetchRow("item_id='".$p['item_id']."' and x_id='".$value."'");
					if(!$check)
						$this->item_exclusive->doCreate($insert);
				}
			}
		}
		
		$this->_redirect("/admin/items/edit/id/".$p['item_id']);
			
	}
	
	/**
	 * removeExclusivityAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function removeExclusivityAction(){
	
		$p = $this->params;
		
		$check = $this->item_exclusive->delete("item_id='".$p['item_id']."' and x_id='".$p['x_id']."'");
		
		$this->_redirect("/admin/items/edit/id/".$p['item_id']);
			
	}
	
	
	/**
	 * deleteStatsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteStatsAction(){
	
		$p = $this->params;
		
		$this->item_stats->doDelete($p['id']);
		
		$this->_redirect("/admin/items/edit/id/".$p['item_id']);
	}
	
	
	/**
	 * listAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function listAction(){
	
		$p = $this->params;
					
		$this->view->t = Model_DbTable_Item::GROCERY;
		if($p['t']){
			$this->view->t = $p['t'];	
		}
		// get parents
		$select = $this->item->select();
		$select->where("id!='1'");	
		$select->where("parent_id='1'");	
		$select->where("eat_type='".$this->view->t."'");	
		$select->order(array("name asc"));
		
		$results = $this->item->fetchAll($select);
	
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);
		
		// get child if requested 
		if(trim($p['parent_id'])){
			
			$this->view->itemData = $this->item->doRead($p['parent_id']);
			$select = $this->item->select();
			$select->where("id!='1'");	
			$select->where("parent_id='".$p['parent_id']."'");	
			$select->order(array("name asc"));
			
			$results = $this->item->fetchAll($select);
		
			$this->view->total = count($results);
			$this->view->paginatorChild = $this->makePagination($results);
		
		}		
	}
	
	
	/**
	 * deleteConfirmAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteConfirmAction(){
	
		$p = $this->params;
		$this->view->id = $p['id'];
		$this->view->item = $this->item->doRead($p['id']);

	}
	
	/**
	 * deleteAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteAction(){
	
		$p = $this->params;
		
		$data = $this->item->doRead($p['id']);
		
		$this->item->doDelete($p['id']);
		
		if($data->parent_id != '1')
			$this->_redirect('admin/items/list/parent_id/'.$data->parent_id);
		else
			$this->_redirect('admin/items/list/');

	}
	
	/**
	 * deleteAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteAllAction(){
	
		$p = $this->params;
		
		$this->item->delete('1=1');
		
		$d['id'] = 1;
		$d['parent_id'] = 1;
		$d['name'] = 'Parent';
		$this->item->doCreate($d);
		
		die;
	}
	
	/**
	 * importAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function importAction(){
		
		//echo "<pre>";
		if (($handle = fopen(DOC_ROOT."/res/items.csv", "r")) !== FALSE) {
		    $i = 0;
		    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		   		
		   		// this is avoid 1st column 
		   		$i++;
		   		if($i == 1)
		   			continue;
		   			
		   		if($data[0]){
		   			$insert['name'] = ucwords(strtolower($data[0]));
		   			$insert['eat_type'] = $data[10];
		   			$insert['parent_id'] = '1';
		   			
		   			$LastItemId = $this->item->doCreate($insert);
		   		}		   		
		   		
	   			if($data[1]){
		   			$insertSub['name'] = ucwords(strtolower($data[1]));
		   			$insertSub['parent_id'] = $LastItemId;
		   			$insertSub['eat_type'] = $data[10];
	  				
	  				$LastSubItemId = $this->item->doCreate($insertSub);
	  			}
	  			
	 			$insertStat['item_id'] 	= $LastSubItemId;
				if($data[2])
		   			$insertStat['weight_unit'] = $data[2];
				$insertStat['weight'] 	= $data[3];
				$insertStat['grams'] 	= $data[4];
				$insertStat['calories'] = $data[5];
				$insertStat['protein'] 	= $data[6];
				$insertStat['fat'] 		= $data[7];
				$insertStat['carbs'] 	= $data[8];
				$insertStat['fiber'] 	= $data[9];
				$this->item_stats->doCreate($insertStat);
				
				// insert all other qty
				if($data[11]){
					$unit_qty = explode(',',$data[11]);
					
					foreach($unit_qty as $key=>$value){
						
						$insertMoreStat['item_id'] = $LastSubItemId;
						$insertMoreStat['weight_unit'] = $data[2];
						$insertMoreStat['weight'] = $value;
						if($data[4])
							$insertMoreStat['grams'] = ($value/$data[3])*$data[4];
						$insertMoreStat['calories'] = ($value/$data[3])*$data[5];
						$insertMoreStat['protein'] = ($value/$data[3])*$data[6];
						$insertMoreStat['fat'] = ($value/$data[3])*$data[7];
						$insertMoreStat['carbs'] = ($value/$data[3])*$data[8];
						$insertMoreStat['fiber'] = ($value/$data[3])*$data[9];
						$this->item_stats->doCreate($insertMoreStat);
						
					}
				}
				
	  			// insert all other qty
				if($data[12]){
					$meal_type = explode(',',$data[12]);
					
					foreach($meal_type as $key=>$value){
						
						$insertMealType['item_id'] = $LastSubItemId;
						$insertMealType['meal_id'] = $value;
						$this->meal_item->doCreate($insertMealType);
						
					}
				}
				
		    }
		    fclose($handle);
		}
		
		die;
		
	}
	
	/**
	 * taskAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function taskAction(){
		
		$this->view->item = $this->item->fetchRow($this->item->select()->order(array('id desc')));
	}
	
	
}