[{$smarty.block.parent}]
[{oxscript add="$('a.js-external').attr('target', '_blank');"}]
[{oxscript include="js/widgets/oxarticlebox.js" priority=10 }]
[{oxscript add="$( 'ul.js-articleBox' ).oxArticleBox();" }]
[{assign var="oConf" value=$oViewConf->getConfig()}]
<div id="rssSlider" class="box">
    <h3 style="text-align:center;">[{oxmultilang ident="ARTICLELIST_TITLE_HEADER1"}]<br/>[{oxmultilang ident="ARTICLELIST_TITLE_HEADER2"}]</h3>
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
                    <strong>[{oxprice price=$article->oxarticles__oxprice->value currency=$oView->getActCurrency()}]</strong></p>
                </a>
            </li>
        [{/foreach}]
    </ul>
</div>