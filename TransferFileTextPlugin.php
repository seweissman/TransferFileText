<?php

/**
 * Transfer File Text plugin.
 */
class TransferFileTextPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('after_save_item','install');

    protected $_filters = array();
    
    public function hookInstall() {
        $items = $this->_db->getTable('Item');
        $item = $items->findFirst();
        while($item != NULL){
            $this->transferFileText($item);
            $item = $items->findNext($item);
        }
    }
    private function transferFileText($item){
        $item_text_element = $item->getElement('Item Type Metadata', 'Text');
        $item->deleteElementTextsByElementId(array($item_text_element->id));
    
        $files = $item->getFiles();
        foreach($files as $file){
            $file_texts = $file->getElementTexts('PDF Text', 'Text');
            foreach($file_texts as $text){
                $item->addTextForElement($item_text_element, $text);
            }        
        }
        $item->saveElementTexts();            
    }



// Note the order of execution.
    public function hookAfterSaveItem($args)
    {
        $item = $args['record'];
        transferFileText($item);
    }


}
