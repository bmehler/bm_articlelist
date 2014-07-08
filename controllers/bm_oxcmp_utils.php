<?php
/**
* Class bm_oxcomp_utils
*
* This Class devlivers a limited number of articles and
* assign them to an array. This array will be rendered and 
* is accessible within the foreach loop of the sidebar.tpl.
*
* @package  bm_articlelist
* @author   Bernhard Mehler bernhard.mehler@gmail.com
* @version  1.0
* @access   public
*/
class bm_oxcmp_utils extends bm_oxcmp_utils_parent
{    
    /**
     * Initialize the class.
     * 
     * The configuration is assigned to $oConf throught a static call of the getConfig() method.
     * To provide a flexible number of articles the limit will be transfered by the backend settings.
     * This setting is assign to the variable iMaxEntries.
     *
     * @return void.
     */    
    public function init()
    {
        $oConf = oxRegistry::getConfig();
        $this->iMaxEntries = $oConf->getShopConfVar( 'iArticleLimit',null,'module:bm_articlelist' );
        parent::init();        
    }
    
    /**
     * Retrieve a certain number of shop articles.
     * 
     * The SQL Statement contains ORDER BY and DESC as well as a LIMIT.
     * The articles will be ordered by the price column.
     * Cause of a reversed order of the articles is want DESC is used.
     * The limit is set in the init method ($this->iMaxEntries).
     * A new Class Object of oxarticlelist is created. This will store the articles.
     *
     * @return object $oArticleList Returns the number of articles.
     */    
    public function getData() 
    {                     
        $sSQL = 'SELECT * FROM oxarticles ORDER BY oxprice DESC LIMIT '.$this->iMaxEntries.'';

        $oArticleList = oxNew('oxarticlelist');
        $oArticleList->selectString($sSQL);
        
        return $oArticleList;    
    }
    
    /**
     * Render the articlelist.
     * 
     * The template parameter articles stored the $oArticlelist Objects and
     * will make them accessible in the sidbar.tpl.
     * In the sidebar.tpl articles Array is assigned to the foreach loop.
     *
     * @return object Returns the number of articles.
     */    
    public function render()
    {               
        $this->_oParent->addTplParam(
            "articles", $this->getData()
        );        
        parent::render();
        return $this;
    }
    
}