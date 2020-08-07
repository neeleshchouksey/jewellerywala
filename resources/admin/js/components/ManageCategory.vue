<template>
    <div class="tabs tabs-default">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#cat" role="tab">Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#sub-cat" role="tab">Sub Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#purity" role="tab">Purity</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#ocassion" role="tab">Ocassion</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#color" role="tab">Color</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="cat" role="tabpanel">
                <category></category>
            </div>
            <div class="tab-pane" id="sub-cat" role="tabpanel">
                <sub-category></sub-category>
            </div>
            <div class="tab-pane" id="purity" role="tabpanel">
                <purity></purity>
            </div>
            <div class="tab-pane" id="ocassion" role="tabpanel">
                <occasion></occasion>
            </div>
            <div class="tab-pane" id="color" role="tabpanel">
                <color></color>
            </div>
        </div>
    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";
    import Category from "./Category";
    import Purity from "./Purity";
    import SubCategory from "./SubCategory";
    import Occasion from "./Occasion";
    import Color from "./Color";

    export default {

        data() {
            return {}
        },
        mixins: [AdminSellerMixin],
        created() {
            //  let that = this;
        },
        components: {
            'category': Category,
            'purity': Purity,
            'sub-category': SubCategory,
            'color': Color,
            'occasion': Occasion

        },
        methods: {},
    }
</script>
