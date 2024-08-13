
const routes = [
  {
    path: "/",
    component: () => import('../pages/LoginPage.vue'),
    redirect: { name: 'login' },
    meta: { guest: true },
    children: [
      {
        path: "/login",
        component: () => import('../components/authentication/Login.vue'),
        name: "login",
      },
      // {
      //   path: "/2fa",
      //   component: () => import('../components/authentication/PreLogin2fa.vue'),
      //   name: "2fa",
      // },
    ]
  },
  //test
  {
    path: "/test",
    component: () => import('../pages/LoginPage.vue'),
    redirect: { name: 'login' },
    meta: { guest: true },
    children: [
      {
        path: "login",
        component: () => import('../components/authentication/TestLogin.vue'),

      },
      {
        path: "2fa",
        component: () => import('../components/authentication/PreLogin2fa.vue'),
        name: "2fa",
      },
    ]
  },

  {
    path: "/2fa",
    component: () => import('../components/authentication/TwoFactorAuth.vue'),
  },

  {
    path: "/forgot-password",
    component: () => import('../pages/ForgotPassword.vue'),
    name: "forgot-password",
    meta: {
      guest: true
    },
    children: [
      {
        path: "email",
        component: () => import('../components/authentication/ResetEmail.vue'),
        name: "email",
      },
      {
        path: "confirmation",
        component: () => import('../components/authentication/EmailConfirmation.vue'),
        name: "email-confirmation",
      },
      {
        path: "/password-reset/:token",
        component: () => import('../components/authentication/ResetPassword.vue'),
      },
    ]
  },

  {
    path: "/user",
    component: () => import('../pages/UserHome.vue'),
    name: "user",
    meta: {
      auth: true
    },
    children: [

      {
        path: "profile",
        component: () => import('../components/base/ProfilePage.vue'),
        name: "UserProfile",
      },
      {
        path: "main",
        component: () => import('../components/user/Main.vue'),
        name: "user-main",
      },
      {
        path: "acc",
        component: () => import('../components/accounting/Main.vue'),
        name: "acc-main",
      }

    ]
  },
  {
    path: "/admin",
    component: () => import('../pages/AdminHome.vue'),
    name: "Admin",
    redirect: { name: 'Dashboard' },
    meta: {
      auth: true,
      role: ['admin'],
      breadcrumb: [{ title: "Home" }]
    },
    children: [
      {
        path: "dashboard",
        component: () => import('../components/admin/Dashboard.vue'),
        name: "Dashboard",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Dashboard" }]
        }
      },
      {
        path: "users",
        component: () => import('../components/admin/Users.vue'),
        name: "Users",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Users" }]
        }
      },
      {
        path: "purchase-manager/:id/:name/suppliers",
        component: () => import('../components/admin/Suppliers.vue'),
        name: "Suppliers",
        meta: {
          breadcrumb: (to) => {
            const param = to.params.name;
            return [{ title: "Home" }, { title: param },{ title: "Suppliers" }]
          }
        }
      },
      {
        path: "sales-manager/:id/:name/customers",
        component: () => import('../components/admin/Customers.vue'),
        name: "Customers",
        meta: {
          breadcrumb: (to) => {
            const param = to.params.name;
            return [{ title: "Home" }, { title: param },{ title: "Customers" }]
          }
        }
      },
      {
        path: "profile",
        component: () => import('../components/base/ProfilePage.vue'),
        name: "Profile",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Profile" }]
        }
      },
      {
        path: "brands",
        component: () => import('../components/admin/Brands.vue'),
        name: "Brands",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Brands" }]
        }
      },
      {
        path: "brands/:id/:name/models",
        component: () => import('../components/admin/Models.vue'),
        name: "Models",
        meta: {
          breadcrumb: (to) => {
            const param = to.params.name;
            return [{ title: "Home" }, { title: param },{ title: "Models" }]
          }
        }
        
      },
      {
        path: "countries",
        component: () => import('../components/admin/Countries.vue'),
        name: "Countries",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Countries" }]
        }
      },
      {
        path: "warehouses",
        component: () => import('../components/admin/Warehouses.vue'),
        name: "Warehouses",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Warehouses" }]
        }
      },
      {
        path: "deactivated-users",
        component: () => import('../components/admin/DeactivatedUsers.vue'),
        name: "Deactivated Users",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Deactivated Users" }]
        }
      },
      {
        path: "security",
        component: () => import('../components/admin/Security.vue'),
        name: "Security",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Security" }]
        }
      },
      {
        path: "invoice-companies",
        component: () => import('../components/admin/InvoiceCompanies.vue'),
        name: "Invoice Companies",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Invoice Companies" }]
        }
      },
      {
        path: "orders",
        component: () => import('../components/admin/Orders.vue'),
        name: "Orders",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Orders" }]
        }
      },
      {
        path: "offers",
        component: () => import('../components/admin/Offers.vue'),
        name: "Offers",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Offers" }]
        }
      },
      {
        path: "confirmation",
        component: () => import('../components/admin/Confirmation.vue'),
        name: "Confirmation",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Confirmation" }]
        }
      },
      {
        path: "expenses-types",
        component: () => import('../components/admin/ExpensesTypes.vue'),
        name: "Expenses Types",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Expenses Types" }]
        }
      },
      {
        path: "accounting",
        component: () => import('../components/admin/Accounting.vue'),
        name: "Accounting",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Accounting" }]
        }
      },
      {
        path: "logistic",
        component: () => import('../components/admin/Logistics.vue'),
        name: "Logistic",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Logistic" }]
        }
      },
      {
        path: "settings",
        component: () => import('../components/admin/Settings.vue'),
        name: "Settings",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Settings" }]
        }
      },

      {
        path: "shipment-services",
        component: () => import('../components/admin/ShipmentServices.vue'),
        name: "Shipment Services",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Shipment Services" }]
        }
      },

      {
        path: "shipment-account/:id",
        component: () => import('../components/admin/ShipmentAccount.vue'),
        name: "Shipment Account",
        meta: {
          breadcrumb: [{ title: "Home" }, { title: "Shipment Account" }]
        }
      },

    ]
  },
  {
    path: "/:notFound(.*)",
    component: () => import('../components/error/404Page.vue'),
    name: "404",
  },
]


export default routes