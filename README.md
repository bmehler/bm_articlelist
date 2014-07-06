Oxid Modul :: bm_articlelist
============================

Mit diesem Modul könnt Ihr die <u>Sidebar eures Shops</U> erweitern.

Bis vor einer Woche war mir Oxid noch fremd. Da ich dies ändern wollte, dachte ich mir, mit einem <u>kleinen
überschaubaren Modul</u> kann ich mich langsam in der Modulentwicklung unter Oxid zurecht finden.
Zu meinem Bedauern musste ich leider feststellen, das es nur bedingt Hilfe im Internet, sei es in Foren oder
in Blogs, gibt. Sehr geholfen haben mir die Beispiele im Github-Repository des [oxid cookbooks](https://github.com/OXIDCookbook) und natürlich das
Buch selbst sowie das Oxid-Forum.

Damit der eine oder andere von euch genau so viel Spass an der <u>Entwicklung</u> von Oxid Modulen findet,
dachte ich mir, ein <u>kleines Tutorial</u> zu erstellen, um einerseits mein Wissen weiterzugeben sowie eine Wissensbasis
aufzubauen. Gerade bei der <u>Modulstruktur</u> sind z.b. bei der Anlage von <u>Übersetzungen</u> verschiedenen Konventionen einzuhalten. Darüberhinaus muss zu Beginn eine **metadata.php** erstellt werden, welche auch nach <u>gewissen Regeln</u> aufgebaut werden muss. Falls ihr noch interessiert sein, möchte ich euch das Tutorial nicht länger vorenthalten. 

Los gehts!

Beschreibung des Moduls bm_articlelist.
---

Das Modul **bm_articlelist** erweitert die **sidebar** um eine <u>weitere Box</u>. Diese Box beinhaltet eine <u>limitierte Anzahl</u> an <u>Shop-Artikeln</u>. Wieviele Artikel angezeigt werden sollen, bestimmt man bequem im <u>Backend</u> unter <u>Einstellungen</u>.
Da ich das Modul für den Einsatz in <u>mehreren Sprachen</u> vorbereiten möchte, befinden sich sowohl für das <u>Frontend</u> als auch für das <u>Backend</u> Language Dateien. Aber dazu später mehr. Um die <u>Artikelbox</u> auch im Frontend für den Kunden ansprechend zu gestalten, entschied ich mich dafür, die Artikel, ähnlich wie bei der Box <u>Top of the Shop</u>, mit <u>JQuery</u> zu animieren. 

Schritt 1 :: Erstellung der Modulstruktur
---

Da Module in Oxid nach dem <u>MVC-Prinzip</u> erstellt werden, bietet sich natürlich an nach dem **modules/modulnamen** die Ordner models, controllers und views anzulegen. Wie Ihr meinem Modul **bm_articlelist** entnehmen könnt, habe ich nach **modules/bm_articlelist** die Ordner controllers und views angelegt. 

Im folgenden die <u>Orderstruktur</u> meines Moduls:

**modules/bm_articlelist/controllers**

**modules/bm_articlelist/views/admin/de**

**modules/bm_articlelist/views/blocks**

**modules/bm_articlelist/translations/de**

Darüberhinaus befinden sich *unter:*

**modules/bm_articlelist/**

die Dateien **metadata.php** und die Datei **screenshot.jpg**.
Letzterer zeigt, nach der Aktivierung des Moduls im Backend einen <u>Screenshot</u> wie das Modul in der Sidebar aussieht.

Innerhalb de oben definierten Ordern werden nun im folgenden <u>Dateien</u> erstellt.

Schritt 2 :: Erstellung der Datei metadata.php
---
```php
<?php
$sMetadataVersion = '1.0';
$aModule = array(
    'id'                        => 'bm_articlelist',
    'title'                     => 'My first Oxid Module',
    'description'               => 'Displays articles on sidebar. Choose your number of items.',
    'thumbnail'                 => 'screenshot.jpg',
    'version'                   => '1.0',
    'author'                    => 'Bernhard Mehler',
    'url'                       => 'https://gitub.com/bmehler/ox_top',
    'email'                     => 'bernhard.mehler@gmail.com',
    'extend'                    => array(
        "oxcmp_utils"           => "bm_articlelist/controllers/bm_oxcmp_utils"
    ),
    'blocks'    => array(
        array(
            'template' =>   'layout/sidebar.tpl',
            'block'    =>   'sidebar_categoriestree',
            'file'     =>   '/views/blocks/sidebar.tpl'
        )
    ),
    'settings'  =>  array(
        array(
            'group'    =>   'main',
            'name'     =>   'iArticleLimit',
            'type'     =>   'str',
            'value'    =>   '5'
        )
    )
);
```

Die **metadata.php** besteht aus *verschiedenen Abschnitten*, welche ich nachfolgend kurz durchgehen möchte.
Selbsterklärend ist der *erste Abschnitt* welcher verschiedene Information zur Version oder zum Author beinhaltet.

Danach folgt als Key des assoziativen Arrays der Begriff **extend**, welcher wiederrum einen Array enthält.
Wie der Name **extend** schon sagt, wird hier eine <u>bestehende Klasse</u> mit einer <u>eigenen Klasse </u>erweitert.
In unserem Fall mit der Klasse **bm_oxcmp_utils**, welche sich im <u>Ordner controllers</u> (siehe oben) befindet.

Nach extend folgt **blocks**. Dieser Teil der **metadata.php** befasst sich mit der Darstellung der Articleliste im Frontend. So wird das *Template* **layout/sidebar.tpl** erweitert. Dargestellt wird die Articlelist im **sidebar_categoriestree**. Schließlich muss noch die <u>eigene tpl Datei</u> unter *file* mit **/views/blocks/sidebar.tpl** angeben werden.

Zu guter Letzt befindet sich im Array noch der Key **settings**. Hier wird das <u>Backend</u> um ein <u>Formularfeld</u> mit dem Namen **iArticleLimit** vom Typ **string** mit dem Wert  **5** erweitert. Diese findet man nach der Aktivierung des Moduls im Reiter *Einstellungen* des Backends.

Als <u>weitere Hilfe</u> empfehle ich euch diesen [Link](http://wiki.oxidforge.org/Features/Extension_metadata_file) , welcher die *einzelnen Strukturelemente* erklärt.

Schritt 3 :: Erstellung der Datei bm_oxcmp_utils
---
```php
<?php
/**
* Class bm_oxcomp_utils
*
* This Class provides a limited number of articles and
* assigns them to an array. This array will be rendered and 
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
     * The configuration is assigned to $oConf through a static call of the getConfig() method.
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
     * Cause of a reversed order of the articles is wanted DESC is used.
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
     * The template parameter articles stores the $oArticlelist Objects and
     * makes them accessible in the sidbar.tpl.
     * The articles Array is accessible in the sidebar.tpl and will be iterated by a foreach loop.
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
```

Wenn man <u>diese Klasse</u> grob überblickt, stellt man fest, dass Sie in *drei Methoden* unterteilt ist. 

Die *erste Methode* **init()** initialisiert z.b. die <u>Konfiguations Parameter</u> unseres Backend-Feldes und stellt dies der Klasse per **$this** zur Verfügung.

Die *zweite Methode* gibt uns, wie der Name **getData()** schon sagt, die Daten zurück. Auf die Erstellung von SQL-Statements möchte ich an dieseser Stelle nicht weiter eingehen. Wie das SQL-Statement schon sagt, werden aus der Tabelle **oxarticles** alle Spalten ausgelesen, diese nach dem Preis (**oxprice**) absteigend (**DESC**) sortiert und mit **LIMIT** auf z.b. 5 Artikel begrenzt. Kurzum wir erhalten eine Liste der fünf teuersten Produkte.

Die *dritte Methode* befasst sich mit dem <u>Rendering</u>, also mit der <u>Übergabe</u> der Daten, an den View, welcher im Modul **bm_articlelist** unter **modules/bm_articlelist/views/blocks/sitebar.tpl** liegt. An dieser Stelle sei angemerkt, dass die Datei **sidebar.tpl ** *im nächsten Schritt* dieses Tutorials erstellt wird. Also keine Sorge.
Damit in der **sidebar.tpl** auch Daten ankommen, wird mit **addTplParam** einmal die <u>Variable,</u> mit der später die Daten in der **sidebar.tpl** anzusprechen sind, definiert. Die Daten selbst werden mit `$this->getData()` zurückgegeben.

Schritt 4 :: Erstellung der Datei sidebar.tp
---
```php
[{$smarty.block.parent}]
[{oxscript add="$('a.js-external').attr('target', '_blank');"}]
[{oxscript include="js/widgets/oxarticlebox.js" priority=10 }]
[{oxscript add="$( 'ul.js-articleBox' ).oxArticleBox();" }]
<div id="articleSlider" class="box">
    <h3 style="text-align:center;">
    [{oxmultilang ident="ARTICLELIST_TITLE_HEADER1"}]<br/>
    [{oxmultilang ident="ARTICLELIST_TITLE_HEADER2"}]</h3>
    <ul class="js-articleBox featuredList">
        [{foreach from=$articles item=article}]
           <li class="articleImage showImage" style="display: list-item;">
                <a href="[{ $article->getMainLink()}]" class="articleBoxImage">
                    <img src="[{$article->getIconUrl()}]"/>
                </a>
            </li>
            <li class="articleTitle">
                <a href="[{ $article->getMainLink()}]">
                    <p>[{ $article->oxarticles__oxtitle->value }]<br/>
                    <strong>
                           [{oxprice price=$article->oxarticles__oxprice->value 
                              currency=$oView->getActCurrency()}]
                    </strong>
                  </p>
                </a>
            </li>
        [{/foreach}]
    </ul>
</div>
```

Nun schenken wir dem **View** unserere Aufmerksamkeit. Wie unschwer zu erkennen ist, handelt es sich hier um HTML-Syntax gepaart z.b. mit einem foreach loop. Damit dies funktioniert, beinhaltet Oxid die <u>Template-Engine Smarty</u>.
Im nachfolgenden möchte ich, für ein besseres Verständnis, die Datei kurz durchgehen.

Mit `[{oxscript add=""}]` wird <u>Javascript</u> eingebunden. Danach folgt ein <u>Div-Element</u> mit der `class="box`. Diese übernimmt nun automatisch die <u>CSS-Eigenschaften</u> für box. Da das Modul mit <u>Mehrsprachigkeit</u> umgehen soll, verwende ich die <u>oxidspezifische Smarty-Funkition</u> `[{oxmulitlang ident=""}]` und übergebe dieser Platzhalter. Wie und wo diese Platzhalter definiert werden, möchte ich später erläutern. Generell erstellen wir eine <u>unordered list</u> mit ul und li Elementen. Um  auf die einzelnen Artikel zuzugreifen wird `$article` mit eine foreach Schleife iteriert. Danach kann bequem per getter-Methoden auf die Eigenschaften des Artikelobjektes zugegriffen werden. Zum Beispiel gibt `[{$article->getMainLink()}]` die SEO-Url des einzelnen Artikels zurück. 

Sicherlich ist dem einen oder anderen von Euch der Aufruf `$oView->getCurrency()` aufgefallen. Hier wird auf das Objekt der View-Klasse zugriffen. Diese steht neben `$oViewConf` immer zur Verfügung und hat Zugriff auf den aktuellen Controller. Zum Beispiel könnt ihr euch verschiedene Konfigurationseinstellungen mit 
`[{assign var="oConf" value=$oViewConf->getConfig()}]` in die Variable `$oConf` holen und dann per `[{$oConf->...}]` auf eine Eigennschaft im Template zugreifen. 

Zuletzt vielleicht noch eins. Mit `$article->oxarticles__oxtitle->value` wird der Artikeltitel eines Artikelobjektes aufgerufen. Demnach kann man mit der Schreibweise `Objekt->Tabelle__Tabellenfeldo->value` auf den Wert zugreifen. So weit so gut. Die Hauptbestandteile dieses Moduls sind wir nun durchgegangen. Ach ja, bevor ich es vergesse, mit `[{debug}]` und mit `[{$article|@var_dump}]` könnt ihr in Smarty debuggen bzw. einen var_dump ausführen.

Schritt 5 :: Erstellung der Dateien für Überssetzugen
---
Nun zum letzten Teil unsres Tutorials, der Mehrsprachigkeit unseres Moduls. Generell wird bei der Oxid Modulentwicklung zwischen Backend und Frontend unterschieden. Nur so viel: Wir benötigen natürlich beides. Zum einen haben wir im Backend ein Formular im Reiter Einstellungen zum anderen haben wir im Frontend die Überschrift unserer Box im Frontend.

Die Spracheinstellungen für das Backend muss im Modul unter **modules/bm_articlelist/views/admin/de** abgelegt werden.
Ich habe die Datei **modul_options.php** genannt. Im folgenden der Code:

```php
<?php

/**
 * Language File for Backend
 */

$sLangName = "Deutsch";
$aLang = array(
    'charset' => 'ISO-8859-15',
    'SHOP_MODULE_GROUP_main'    => 'Grundeinstellungen',    
    'SHOP_MODULE_iArticleLimit' => 'maximal angezeigte Einträge',
);
```
Die Spracheinstellungen für das Frontend hingegen müssen im Modul unter **modules/bm_articlelist/translations/de** abgelegt werden. Diese Datei habe ich im Modul **bm_articlelist_lang** genannt. Der Code hierzu:

```php
<?php

/**
* Language File for Frontend
*/

$sLangName = "Deutsch";
$aLang = array(
    'charset' => 'utf-8',
    'ARTICLELIST_TITLE_HEADER1' => 'Auf Ihrem Wunschzettel?',
    'ARTICLELIST_TITLE_HEADER2' => 'Produkte mit Fun-Garantie!',
);
```
Das wars fürs erste. Große Freude hätte ich, wenn ich dem einen oder anderen von Euch  mit meinem Tutorial helfen und somit den Einstieg in die Modulentwicklung mit Oxid erleichtern konnte.

