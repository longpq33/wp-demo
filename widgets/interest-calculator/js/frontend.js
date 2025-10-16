(function ($) {
  function parseMoney(str) {
    if (typeof str === 'number') return str;
    return parseInt(String(str).replace(/[^0-9]/g, ''), 10) || 0;
  }
  function formatVND(n) {
    // Use comma as thousands separator
    return (n || 0).toLocaleString('en-US');
  }
  // Convert number to Vietnamese words (basic, up to trillion+)
  function numberToVietnameseWords(num) {
    num = Math.floor(Math.abs(num || 0));
    if (num === 0) return 'Không';
    var units = ['', ' nghìn', ' triệu', ' tỷ', ' nghìn tỷ', ' triệu tỷ', ' tỷ tỷ'];
    var digit = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
    function readHundreds(n, forceZeroHundreds) {
      var tr = Math.floor(n / 100), ch = Math.floor((n % 100) / 10), dv = n % 10;
      var str = '';
      if (tr > 0) { str += digit[tr] + ' trăm'; }
      else if (forceZeroHundreds && (ch > 0 || dv > 0)) { str += 'không trăm'; }
      if (ch > 1) { str += (str ? ' ' : '') + digit[ch] + ' mươi'; if (dv === 1) str += ' mốt'; else if (dv === 4) str += ' tư'; else if (dv === 5) str += ' lăm'; else if (dv > 0) str += ' ' + digit[dv]; }
      else if (ch === 1) { str += (str ? ' ' : '') + 'mười'; if (dv === 5) str += ' lăm'; else if (dv > 0) str += ' ' + digit[dv]; }
      else if (ch === 0 && dv > 0) { if (tr > 0 || forceZeroHundreds) str += ' linh'; str += (str ? ' ' : '') + digit[dv]; }
      return str.trim();
    }
    var parts = [];
    var unitIndex = 0;
    while (num > 0 && unitIndex < units.length) {
      var group = num % 1000;
      if (group > 0) {
        var remaining = Math.floor(num / 1000); // higher groups still exist
        var forceZeroHundreds = (group < 100) && (remaining > 0);
        var groupWords = readHundreds(group, forceZeroHundreds) + units[unitIndex];
        parts.unshift(groupWords.trim());
      }
      num = Math.floor(num / 1000);
      unitIndex++;
    }
    var out = parts.join(' ').replace(/\s+/g, ' ').trim();
    // Capitalize first letter
    return out.charAt(0).toUpperCase() + out.slice(1);
  }

  function termDays(term) {
    if (term === '1d') return 1;
    if (term === '4y_15y') return 365 * 4; // default to 4 years per spec
    var m = term.match(/(\d+)m/);
    if (!m) return 0;
    var months = parseInt(m[1], 10);
    // Days per month in a typical year (not leap-aware for product terms)
    var monthDays = [31,28,31,30,31,30,31,31,30,31,30,31];
    var days = 0;
    // Sum full years
    var fullYears = Math.floor(months / 12);
    if (fullYears > 0) {
      days += fullYears * monthDays.reduce(function(a,b){ return a+b; }, 0);
    }
    // Sum remaining months starting from January baseline
    var remaining = months % 12;
    for (var i = 0; i < remaining; i++) {
      days += monthDays[i % 12];
    }
    return days;
  }

  function fillPackages($container, type) {
    var map = (MSBInterestData.packagesByType || {})[type] || [];
    var options = {
      highest: 'Lãi suất cao nhất',
      partial: 'Rút gốc từng phần',
      periodic: 'Định kỳ sinh lời',
      pay_now: 'Trả lãi ngay',
      bee: 'Ong vàng',
      sprout: 'Măng non',
      deposit_contract: 'Hợp đồng tiền gửi'
    };
    var $pkg = $container.find('.msb-ic-package');
    $pkg.empty();
    map.forEach(function (key) {
      $pkg.append('<option value="' + key + '">' + (options[key] || key) + '</option>');
    });
  }

  function fillCurrencies($container) {
    var currencies = ['AUD', 'EUR', 'CAD', 'JPY', 'GBP', 'SGD', 'USD'];
    var currencyNames = {
      'AUD': 'Australian Dollar',
      'EUR': 'Euro',
      'CAD': 'Canadian Dollar',
      'JPY': 'Japanese Yen',
      'GBP': 'British Pound',
      'SGD': 'Singapore Dollar',
      'USD': 'US Dollar'
    };
    var $currency = $container.find('.msb-ic-currency');
    $currency.empty();
    currencies.forEach(function (code) {
      $currency.append('<option value="' + code + '">' + code + ' - ' + currencyNames[code] + '</option>');
    });
  }

  function toggleForeignCurrencyFields($container, isForeign) {
    var $packageLabel = $container.find('.msb-ic-package-label');
    var $package = $container.find('.msb-ic-package');
    var $currencyLabel = $container.find('.msb-ic-currency-label');
    var $currency = $container.find('.msb-ic-currency');
    
    if (isForeign) {
      $packageLabel.hide();
      $package.hide();
      $currencyLabel.show();
      $currency.show();
    } else {
      $packageLabel.show();
      $package.show();
      $currencyLabel.hide();
      $currency.hide();
    }
  }

  function fillTerms($container, type){
    var list = (MSBInterestData.termsByType||{})[type]||[];
    var $term = $container.find('.msb-ic-term');
    var current = $term.val();
    function labelFor(k){
      if(k==='1d') return '1 ngày';
      var m = k.match(/(\d+)m/); if(m) return m[1] + ' tháng';
      if(k==='4y_15y') return '4 năm – 15 năm';
      return k;
    }
    $term.empty();
    list.forEach(function(k){ $term.append('<option value="'+k+'">'+labelFor(k)+'</option>'); });
    // restore if still valid else pick first
    if(list.indexOf(current) >= 0){ $term.val(current); }
  }

  function calc($container) {
    var amount = parseMoney($container.find('.msb-ic-amount').val());
    var type = $container.find('.msb-ic-type').val();
    var term = $container.find('.msb-ic-term').val();
    var rates = MSBInterestData.rates || {};
    var rate = null;

    if (type === 'foreign') {
      var currency = $container.find('.msb-ic-currency').val();
      rate = (((rates[type] || {})[currency] || {})[term]) || null;
    } else {
      var pkg = $container.find('.msb-ic-package').val();
      rate = (((rates[type] || {})[pkg] || {})[term]) || null;
    }

    if (rate == null) {
      $container.find('.msb-ic-rate').text('— %');
      $container.find('.msb-ic-interest').text('— VND');
      $container.find('.msb-ic-total-value').text('—');
      $container.find('.msb-ic-cta').addClass('disabled');
      return;
    }
    var days = termDays(term);
    var interest = amount * (rate / 100) * (days / 365);
    var total = amount + interest;
    $container.find('.msb-ic-rate').text(rate.toFixed(2) + ' %');
    $container.find('.msb-ic-interest').text(formatVND(Math.round(interest)) + ' VND');
    $container.find('.msb-ic-total-value').text(formatVND(Math.round(total)));
    $container.find('.msb-ic-cta').removeClass('disabled');
  }

  function updateAmountHint($container) {
    var amount = parseMoney($container.find('.msb-ic-amount').val());
    if (!amount) {
      $container.find('.msb-ic-amount-hint').text('');
      return;
    }
    var words = numberToVietnameseWords(amount) + ' đồng';
    $container.find('.msb-ic-amount-hint').text(words);
  }

  $(function () {
    $('.msb-interest-calculator').each(function () {
      var $root = $(this);
      var initType = $root.find('.msb-ic-type').val();
      
      // Initialize currency dropdown
      fillCurrencies($root);
      
      // Initialize based on type
      if (initType === 'foreign') {
        toggleForeignCurrencyFields($root, true);
      } else {
        fillPackages($root, initType);
        toggleForeignCurrencyFields($root, false);
      }
      
      fillTerms($root, initType);
      calc($root);
      updateAmountHint($root);

      $root.on('input', '.msb-ic-amount', function () {
        var $el = $(this);
        var amt = parseMoney($el.val());
        $el.val(formatVND(amt));
        updateAmountHint($root);
        calc($root);
      });
      
      $root.on('change', '.msb-ic-term, .msb-ic-package, .msb-ic-currency', function () { 
        calc($root); 
      });
      
      $root.on('change', '.msb-ic-type', function () { 
        var t = $(this).val();
        if (t === 'foreign') {
          toggleForeignCurrencyFields($root, true);
        } else {
          fillPackages($root, t);
          toggleForeignCurrencyFields($root, false);
        }
        fillTerms($root, t);
        calc($root);
      });
    });
  });
})(jQuery);


