(function () {
    var $tree = $('.ns-jqtree-js');
    var dndUrl = $tree.data('dnd-url');
    var Tree = {
        /**
         * set selected node on plugin init
         */
        init: function () {
            Tree._setSelectedNode();
        },

        _setSelectedNode: function () {
            var selectedNode = $tree.data('selected-node-id');

            if (selectedNode) {
                var node = $tree.tree('getNodeById', selectedNode);
                $tree
                    .tree('selectNode', node)
                    .tree('openNode', node);
            }
        },

        /**
         * don't set node active on click
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
                    if (data.error) {
                        alert(data.error);
                    } else {
                        event.move_info.do_move();
                    }
                },
                error: function () {
                    alert('Server error.')
                },
                url: dndUrl
            });
        }
    };

    $tree
        .bind('tree.init', Tree.init)
        .bind('tree.click', Tree.click);

    if (dndUrl) {
        $tree.bind('tree.move', Tree.move);
    }
})();