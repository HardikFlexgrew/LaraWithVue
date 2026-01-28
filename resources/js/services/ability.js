import { defineAbility } from "@casl/ability";

export default (role) => {
    return defineAbility(can => {
        switch (role) {
            case 'Admin':
                can(['add','edit','delete','show','cart'],'product');
                break;
            default:
                can(['show','cart'],'product');
                break;
        }
    }) 
}