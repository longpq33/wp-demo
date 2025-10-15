(function($){
  function labelTerm(k){
    if(k==='1d') return 'Rút trước hạn';
    var m=k.match(/(\d+)m/); if(m){ return (m[1].length===1?'0':'')+m[1]+' tháng'; }
    if(k==='4y_15y') return '4 năm – 15 năm';
    return k;
  }
  function unique(arr){ return Array.from(new Set(arr)); }

  function buildPackageOptions($root){
    var data=MSBSavingRatesData.data||{};
    var pbt=(data.packagesByType||{});
    var list=[].concat(pbt.online||[], pbt.counter||[]);
    list=unique(list);
    var labels=(MSBSavingRatesData.labels||{}).packages||{};
    var $sel=$root.find('.msb-sr-package-select');
    $sel.empty();
    list.forEach(function(key){ $sel.append('<option value="'+key+'">'+(labels[key]||key)+'</option>'); });
  }

  function termsByBucket(bucket){
    if(bucket==='0_6') return ['1d','1m','2m','3m','4m','5m','6m'];
    if(bucket==='6_12') return ['7m','8m','9m','10m','11m','12m'];
    return ['13m','15m','18m','24m','36m','4y_15y'];
  }

  function renderTable($root){
    var data=MSBSavingRatesData.data||{};
    var bucket=$root.find('input[name="msb-sr-bucket"]:checked').val();
    var pkg=$root.find('.msb-sr-package-select').val();
    var online=((data.rates||{}).online||{})[pkg]||{};
    var counter=((data.rates||{}).counter||{})[pkg]||{};
    var terms=termsByBucket(bucket);
    var $tbody=$root.find('.msb-sr-table tbody');
    $tbody.empty();
    terms.forEach(function(t){
      // Hide unsupported terms per type naturally by showing — when absent
      var c = (t in counter) ? counter[t] : '—';
      var o = (t in online) ? online[t] : '—';
      // For 1d term: only meaningful for online; keep counter as —
      if(t==='1d'){ c = (c===0||c==='—') ? '—' : c; }
      $tbody.append('<tr><td>'+labelTerm(t)+'</td><td>'+(c==='—'?c:(c.toFixed?c.toFixed(1):c))+' '+(c==='—'?'':'%')+'</td><td>'+(o==='—'?o:(o.toFixed?o.toFixed(1):o))+' '+(o==='—'?'':'%')+'</td></tr>');
    });
  }

  $(function(){
    $('.msb-saving-rates').each(function(){
      var $root=$(this);
      buildPackageOptions($root);
      // defaults
      var defaults = (MSBSavingRatesData.defaults||{});
      $root.find('.msb-sr-package-select').val(defaults.package||'periodic');
      $root.find('input[name="msb-sr-bucket"][value="'+(defaults.bucket||'0_6')+'"]').prop('checked', true);
      renderTable($root);

      $root.on('change','input[name="msb-sr-bucket"], .msb-sr-package-select', function(){ renderTable($root); });
    });
  });
})(jQuery);


