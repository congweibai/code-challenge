<template>
    <div id="mymedia">
        <page-navbar></page-navbar>
        <media-page></media-page> 
        <return-top/>
    </div>
</template>

<script>
import pageNavbar from './components/commonuse/pageNavbar';
import mediaPage from './components/mediaPage';
import returnTop from './components/commonuse/returnButton';

export default {
    name: 'mymedia',
    components: {
        pageNavbar,
        returnTop,
        mediaPage
    }
}
</script>
