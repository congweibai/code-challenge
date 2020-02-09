<template>
    <div id="searchPage">
        <page-navbar></page-navbar>
        <search-mainbody></search-mainbody>
        <return-top/>
    </div>
</template>

<script>
import searchMainbody from './components/searchMainbody';
import pageNavbar from './components/commonuse/pageNavbar';
import returnTop from './components/commonuse/returnButton';

export default {
    name: 'searchPage',
    components: {
        searchMainbody,
        pageNavbar,
        returnTop
    }
}
</script>
