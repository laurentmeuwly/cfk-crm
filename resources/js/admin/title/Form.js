import AppForm from '../app-components/Form/AppForm';

Vue.component('title-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                locale:  '' ,
                
            }
        }
    }

});