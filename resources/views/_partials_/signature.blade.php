<script>
    /*
    jQuery pub/sub plugin by Peter Higgins (dante.org)
     Loosely based on Dojo publish/subscribe API, limited in scope. Rewritten blindly.
     Original is (c) Dojo Foundation 2004-2010. Released under either AFL or new BSD, see:
     http://dojofoundation.org/license for more information.
     */
    (function($) {
        var topics = {};
        $.publish = function(topic, args) {
            if (topics[topic]) {
                var currentTopic = topics[topic],
                    args = args || {};

                for (var i = 0, j = currentTopic.length; i < j; i++) {
                    currentTopic[i].call($, args);
                }
            }
        };
        $.subscribe = function(topic, callback) {
            if (!topics[topic]) {
                topics[topic] = [];
            }
            topics[topic].push(callback);
            return {
                "topic": topic,
                "callback": callback
            };
        };
        $.unsubscribe = function(handle) {
            var topic = handle.topic;
            if (topics[topic]) {
                var currentTopic = topics[topic];

                for (var i = 0, j = currentTopic.length; i < j; i++) {
                    if (currentTopic[i] === handle.callback) {
                        currentTopic.splice(i, 1);
                    }
                }
            }
        };
    })(jQuery);

</script>
<script src=" {{ asset('js/siggy/jSignature.js') }} "></script>
<script src="{{ asset('js/siggy/plugins/jSignature.CompressorBase30.js') }} "></script>
<script src="{{ asset('js/siggy/plugins/jSignature.CompressorSVG.js') }} "></script>
<script src="{{ asset('js/siggy/plugins/jSignature.UndoButton.js') }} "></script>
<script src="{{ asset('js/siggy/plugins/signhere/jSignature.SignHere.js') }} "></script>
<script>
    $(document).ready(function() {
        var $sigdiv = $("#signature").jSignature({'UndoButton':false});

        // -- i explain from here...
        $('#btnSave').click(function(){
            var sigData = $('#signature').jSignature('getData','default');
            $('#digital_signature').val(sigData);
        });
        // -- ... to here.

    })
</script>
