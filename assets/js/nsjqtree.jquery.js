(function () {
    var
        $tree = $('.ns-jqtree-js'),
        dndUrl = $tree.data('dnd-url'),
        Tree = {
            /**
             * set selected node on plugin init
             */
            inint: function () {
                var selectedNode = $tree.data('selected-node');

                if (selectedNode) {
                    var node = $tree.tree('getNodeById', selectedNode);
                    $tree
                        .tree('selectNode', node)
                        .tree('openNode', node);
                }
            },

            /**
             * dont set node active on click
             * @param event
             * @returns {boolean}
             */
            click: function (event) {
                event.preventDefault();
                return false;
            },

            /**
             * move node
             * @param event
             */
            move: function (event) {
                $.ajax({
                    data: {
                        'moved': event.move_info.moved_node.id,
                        'target': event.move_info.target_node.id,
                        'position': event.move_info.position
                    },
                    type: 'post',
                    success: function (data) {
                        if (!data.error) {
                            event.move_info.do_move();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Server error.')
                    },
                    url: dndUrl
                });
            }
        };


    $tree
        .bind('tree.init', Tree.inint)
        .bind('tree.click', Tree.click);

    if (dndUrl) {
        $tree.bind('tree.move', Tree.move);
    }

})();