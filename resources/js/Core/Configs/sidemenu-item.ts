import { MenuItem } from "primevue/menuitem";

const basicRoute = (routeName : string, params = {}) => route(routeName,params,false);

export interface SideMenuItem extends MenuItem {
    permissions?: string | string[];
    items?: SideMenuItem[];
}

export const sideMenuItemData: SideMenuItem[] = [
    {
        label: 'Menu',
        separator: true,
    },
    {
        label: 'Dashboard',
        icon: 'pi pi-home',
        url: basicRoute('dashboard'),
    },
    {
        label: 'Konfigurasi SKM',
        icon: 'pi pi-database',
            items: [
                {
                    label: 'SKM',
                    // url: basicRoute('skm.browse'), // jika ada route untuk SKM utama
                },
                {
                    label: 'Layanan',
                    url: basicRoute('service.browse'),
                },
            ]
    },
    // {
    //     label: 'User Management',
    //     icon: 'pi pi-box',
    //     permissions: ['user.browse','role.browse','user_activity.browse'],
    //     items: [
    //         { 
    //             label: 'Users',
    //             url: basicRoute('user.browse'),
    //             permissions: 'user.browse',
    //         }, 
    //         { 
    //             label: 'Roles & Permissions',
    //             url: basicRoute('role.browse'),
    //             permissions: 'role.browse',
    //         }, 
    //         { 
    //             label: 'User Activity',
    //             url: basicRoute('user_activity.browse'),
    //             permissions: 'user_activity.browse',
    //         }
    //     ]
    // },
]