(function($){
  $(document).ready(function(){
    var $wrap = $('[id^="msb-menu-items-"]');
    if(!$wrap.length) return;

    $wrap.each(function(){
      var $w = $(this);
      var $container = $w.find('.msb-items-container');
      var baseName = $container.data('base-name');
      var itemIndex = $container.find('.msb-item-row').length;

      $w.on('click', '.msb-add-item', function(e){
        e.preventDefault();
        var html = ''
          + '<div class="msb-item-row">'
          +   '<div class="msb-media-control">'
          +     '<label>Icon:</label>'
          +     '<div class="msb-media-preview" style="margin:6px 0;"></div>'
          +     '<input type="hidden" class="msb-media-id" name="' + baseName + '[' + itemIndex + '][icon]" value="">'
          +     '<button type="button" class="button msb-media-select">Chọn icon</button>'
          +     '<button type="button" class="button msb-media-remove" style="margin-left:6px; display:none;">Xóa</button>'
          +     '<p style="margin-top:6px; color:#666; font-size:12px;">Bạn cũng có thể nhập class Dashicons.</p>'
          +   '</div>'
          +   '<p><label>Text:</label>'
          +   '<input type="text" name="' + baseName + '[' + itemIndex + '][text]" placeholder="Trang chủ"></p>'
          +   '<p><label>URL:</label>'
          +   '<input type="url" name="' + baseName + '[' + itemIndex + '][url]" placeholder="https://example.com"></p>'
          +   '<button type="button" class="msb-remove-item">Xóa</button>'
          + '</div>';
        $container.append(html);
        itemIndex++;
      });

      $w.on('click', '.msb-media-select', function(e){
        e.preventDefault();
        var $row = $(this).closest('.msb-item-row');
        var frame = wp.media({ title: 'Chọn icon', multiple: false, library: { type: 'image' } });
        frame.on('select', function(){
          var attachment = frame.state().get('selection').first().toJSON();
          $row.find('.msb-media-id').val(attachment.id);
          var imgUrl = (attachment.sizes && attachment.sizes.thumbnail) ? attachment.sizes.thumbnail.url : attachment.url;
          $row.find('.msb-media-preview').html('<img src="'+imgUrl+'" style="max-width:48px;max-height:48px;" />');
          $row.find('.msb-media-remove').show();
        });
        frame.open();
      });

      $w.on('click', '.msb-media-remove', function(e){
        e.preventDefault();
        var $row = $(this).closest('.msb-item-row');
        $row.find('.msb-media-id').val('');
        $row.find('.msb-media-preview').empty();
        $(this).hide();
      });

      $w.on('click', '.msb-remove-item', function(e){
        e.preventDefault();
        $(this).closest('.msb-item-row').remove();
      });
    });
  });
})(jQuery);


