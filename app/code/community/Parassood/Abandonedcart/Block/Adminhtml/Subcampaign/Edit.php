<?php

class Parassood_Abandonedcart_Block_Adminhtml_Subcampaign_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    /**
     * Variable to store Cause instance
     *
     * @var null|Parassood_Abandonedcart_Model_Subcampaign
     */
    protected $_subcampaign = null;

    public function __construct()
    {
        $this->_objectId    = 'subcampaign_id';
        $this->_controller  = 'adminhtml_subcampaign';
        $this->_blockGroup  = 'parassood_abandonedcart';
        parent::__construct();
        //$this->removeButton('delete');

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        $this->_formScripts[] = "function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }";
    }

    /**
     * Get header text for RMA edit page
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->getSubcampaign()->getSubcampaignId()) {
            return Mage::helper('parassood_abandonedcart')
                ->__("Sub-Campaign # %s ",
                    intval($this->getSubcampaign()->getSubcampaignId()));
        }
    }

    /**
     * Declare region instance
     *
     * @return  Parassood_Abandonedcart_Model_Subcampaign
     */
    public function getSubcampaign()
    {
        if (is_null($this->_subcampaign)) {
            if(!Mage::registry('current_subcampaign')){
                $subcampaign = Mage::getModel('parassood_abandonedcart/subcampaign');
                Mage::register('current_subcampaign', $subcampaign);
            }
            $this->_subcampaign = Mage::registry('current_subcampaign');
        }
        return $this->_subcampaign;
    }
}