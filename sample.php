<?php
include_once('simple_html_dom.php');
$temp = '<html><head></head><body><span><div style="display:none">5</div>44<span style="display:none">24</span><span></span><span style="display:none">37</span><span class="lWd6">45</span><span style="display:none">67</span><div style="display:none">67</div><span class="lWd6">86</span><span style="display:none">164</span><span class="NQU0">164</span><span class="ITtC">201</span><span style="display: inline">.</span><span></span><span style="display: inline">65</span><span class="116">.</span><span style="display:none">2</span><span class="NQU0">2</span><span class="lWd6">10</span>24<div style="display:none">34</div><div style="display:none">48</div><span style="display:none">51</span><span class="NQU0">51</span><span style="display:none">87</span><span class="NQU0">87</span><span style="display:none">115</span><div style="display:none">115</div><span style="display:none">160</span><span style="display:none">202</span><div style="display:none">202</div><span style="display:none">213</span><span class="lWd6">213</span><div style="display:none">213</div><span style="display:none">221</span><span class="lWd6">221</span><span style="display:none">228</span><span class="lWd6">228</span>.<span class="NQU0">15</span><span class="NQU0">25</span><span style="display:none">39</span><span class="NQU0">39</span><span></span><span class="NQU0">92</span><span></span><span class="lWd6">139</span><span></span><span class="lWd6">169</span><div style="display:none">169</div><span class="lWd6">228</span><span></span><span class="lWd6">231</span><span class="143">245</span></span></body></html>';
$html = str_get_html($temp);

foreach ($html->children as $value) {
	echo $value . "\n";
}
?>