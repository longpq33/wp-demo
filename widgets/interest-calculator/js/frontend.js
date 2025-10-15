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
    var m = term.match(/(\d+)m/);
    return m ? parseInt(m[1], 10) * 30 : 0;
  }

  function fillPackages($container, type) {
    var map = (MSBInterestData.packagesByType || {})[type] || [];
    var options = {
      highest: 'Lãi suất cao nhất',
      partial: 'Rút gốc từng phần',
      periodic: 'Định kỳ sinh lời',
      pay_now: 'Trả lãi ngay',
      bee: 'Ong vàng'
    };
    var $pkg = $container.find('.msb-ic-package');
    $pkg.empty();
    map.forEach(function (key) {
      $pkg.append('<option value="' + key + '">' + (options[key] || key) + '</option>');
    });
  }

  function calc($container) {
    var amount = parseMoney($container.find('.msb-ic-amount').val());
    var type = $container.find('.msb-ic-type').val();
    var pkg = $container.find('.msb-ic-package').val();
    var term = $container.find('.msb-ic-term').val();
    var rates = MSBInterestData.rates || {};
    var rate = (((rates[type] || {})[pkg] || {})[term]) || null;

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
      fillPackages($root, $root.find('.msb-ic-type').val());
      calc($root);
      updateAmountHint($root);

      $root.on('input', '.msb-ic-amount', function () {
        var $el = $(this);
        var amt = parseMoney($el.val());
        $el.val(formatVND(amt));
        updateAmountHint($root);
        calc($root);
      });
      $root.on('change', '.msb-ic-term, .msb-ic-package', function () { calc($root); });
      $root.on('change', '.msb-ic-type', function () { fillPackages($root, $(this).val()); calc($root); });
    });
  });
})(jQuery);


