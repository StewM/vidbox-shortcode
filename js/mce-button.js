(function() {
    tinymce.PluginManager.add('vidbox', function( editor, url ) {
        var sh_tag = 'vidbox';
        editor.addCommand('vidbox_popup', function(ui, v) {
          //setup defaults
          var video_url = '';
          if (v.video_url)
              video_url = v.video_url;
          var auto_play = 'yes';
          if (v.auto_play)
              auto_play = v.auto_play;
          var alt_text = '';
          if (v.alt_text)
              alt_text = v.alt_text;
          //open the popup
          editor.windowManager.open( {
              title: 'Video Lightbox Shortcode',
              body: [
                  {//add video url input
                      type: 'textbox',
                      name: 'video_url',
                      label: 'YouTube URL',
                      value: video_url,
                      tooltip: 'Required'
                  },
                  {//add auto play
                      type: 'listbox',
                      name: 'auto_play',
                      label: 'Auto Play',
                      value: auto_play,
                      'values': [
                        {text: 'Yes', value: 'yes'},
                        {text: 'No', value: 'no'}
                      ],
                      tooltip: 'yes or no'
                  },
                  {//add alt text
                      type: 'textbox',
                      name: 'alt_text',
                      label: 'Alt Text',
                      value: alt_text,
                      tooltip: 'Enter Alt Text'
                  }
              ],
              onsubmit: function( e ) { //when the ok button is clicked
                  //start the shortcode tag
                  var shortcode_str = '[' + sh_tag + ' video_url="'+e.data.video_url+'"';

                  shortcode_str += ' auto_play="' + e.data.auto_play + '"';
                  //check for footer
                  if (typeof e.data.alt_text != 'undefined' && e.data.alt_text.length)
                      shortcode_str += ' alt_text="' + e.data.alt_text + '"';

                  //end shortcode
                  shortcode_str += '][/' + sh_tag + ']';

                  //insert shortcode to TinyMCE
                  editor.insertContent( shortcode_str);
              }
          });
      });

      //add button
      editor.addButton('vidbox', {
          icon: 'vidbox',
          tooltip: 'Video Lightbox',
          onclick: function() {
              editor.execCommand('vidbox_popup','',{
                  video_url : '',
                  auto_play : 'yes',
                  alt_text  : ''
              });
          }
      });
    });
})();
