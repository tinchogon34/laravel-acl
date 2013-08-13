<?php

/**
 * Test class for Acl\Manager
 *
 * @group acl
 * @group acl.manager
 */
class ManagerTest extends Orchestra\Testbench\TestCase
{

    protected function getPackageProviders()
    {
        return array('VivifyIdeas\Acl\AclServiceProvider');
    }

    protected function getPackageAliases()
    {
        return array(
            'AclManager' => 'VivifyIdeas\Acl\Facades\Manager',
        );
    }

    /**
     * Testing reloadPermissions method
     */
    public function testReloadPermissions()
    {
        $this->assertEquals(array(), AclManager::reloadPermissions(true));
    }

    public function testReloadGroups()
    {
        $expected = array(
            'ADMIN_PRIVILEGES' => null,
            'MANAGE_STUFF' => 'ADMIN_PRIVILEGES',
            'MANAGE_PRODUCTS' => 'ADMIN_PRIVILEGES',
            'MANAGE_USERS' => 'ADMIN_PRIVILEGES',
            'MANAGE_SPEC_USER' => 'MANAGE_USERS',
            'STUFF_PRIVILEGES' => null
        );

        $this->assertEquals($expected, AclManager::reloadGroups());
    }

    public function testGetAllPermissionsGrouped()
    {
        $expected = array(
            array(
                'id' => 'ADMIN_PRIVILEGES',
                'name' => 'Administrator Privileges',
                'children' => array(
                    array(
                        'id' => 'MANAGE_STUFF',
                        'name' => 'Manage Stuff'
                    ),
                    array(
                        'id' => 'MANAGE_PRODUCTS',
                        'name' => 'Manage Products',
                        'children' => array(
                            array(
                                'id' => 'EDIT_PRODUCT',
                                'allowed' => true,
                                'route' => array('GET:/products/(\d+)/edit', 'PUT:/products/(\d+)'),
                                'resource_id_required' => true,
                                'name' => 'Edit product',
                                'group_id' => 'MANAGE_PRODUCTS'
                            ),
                            array(
                                'id' => 'VIEW_PRODUCT',
                                'allowed' => true,
                                'route' => 'GET:/products/(\d+)$',
                                'resource_id_required' => true,
                                'name' => 'View product',
                                'group_id' => 'MANAGE_PRODUCTS'
                            ),
                            array(
                                'id' => 'CREATE_PRODUCT',
                                'allowed' => true,
                                'route' => array('GET:/products/create', 'POST:/products'),
                                'resource_id_required' => false,
                                'name' => 'Create product',
                                'group_id' => 'MANAGE_PRODUCTS'
                            )
                        )
                    ),
                    array(
                        'id' => 'MANAGE_USERS',
                        'name' => 'Manage Users',
                        'children' => array(
                            array(
                                'id' => 'MANAGE_SPEC_USER',
                                'name' => 'Manage spec user',
                            ),
                            array(
                                'id' => 'EDIT_USER',
                                'allowed' => true,
                                'route' => array('GET:/users/(\d+)/edit', 'PUT:/users/(\d+)'),
                                'resource_id_required' => true,
                                'name' => 'Edit user',
                                'group_id' => 'MANAGE_USERS'
                            ),
                            array(
                                'id' => 'VIEW_USER',
                                'allowed' => false,
                                'route' => 'GET:/users/(\d+)$',
                                'resource_id_required' => true,
                                'name' => 'View user',
                                'group_id' => 'MANAGE_USERS'
                            )
                        )
                    )
                )
            ),
            array(
                'id' => 'STUFF_PRIVILEGES',
                'name' => 'Stuff Privileges',
            ),
            array(
                'id' => 'LIST_PRODUCTS',
                'allowed' => true,
                'route' => 'GET:/products',
                'resource_id_required' => false,
                'name' => 'List products',
            )
        );

        $actual = AclManager::getAllPermissionsGrouped();

        $this->assertEquals($expected, AclManager::getAllPermissionsGrouped());
    }


}