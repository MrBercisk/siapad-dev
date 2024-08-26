<?php defined('BASEPATH') or exit('No direct script access allowed');
class Muser extends CI_Model
{

    public function formInsert()
    {
        $uptdData = $this->db->get('mst_uptd')->result();
        $opsiuptd = '';
        foreach ($uptdData as $uptd) {
            $opsiuptd .= '<option value="' . $uptd->id . '">' . $uptd->nama . '</option>';
        }
        $enum = ['adm', 'man', 'opr', 'pjb', 'kadis', 'uptd', 'mhs', 'pjk', 'bpk'];
        $form[] = '
			  <form action="' . site_url('master/usermanagement/aksi') . '" method="post" enctype="multipart/form-data" class="form-row">
            <div class="row">
                <div class="col-md-6">
                    ' . implode($this->Form->inputText('login', 'Login')) . '
                </div>
                <div class="col-md-6">
                    ' . implode($this->Form->inputText('username', 'Username')) . '
                </div>
                <div class="col-md-6">
                  ' . implode($this->Form->inputPassword('passwd', 'Password')) . '
                </div>
                <div class="col-md-6">
                    ' . $this->Form->inputEnumOptions('role', 'Role', $enum) . '
                </div>
               <div class="col-md-6 offset-3">
                    <div class="form-group">
                        <label for="iduptd">Nama Kecamatan/UPTD</label>
                        <select name="iduptd" id="iduptd" class="form-control">
                            ' . $opsiuptd . '
                        </select>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="btn-group">
                        <button class="btn btn-outline-danger mr-1" type="reset">
                            <i class="fa fa-undo"></i> Reset
                        </button>
                        <button class="btn btn-outline-primary" type="submit" name="AKSI" value="Save">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>';

        return $form;
    }
    public function fromRole()
    {
        $uptdData = $this->db->get('mst_uptd')->result();
        $opsiuptd = '';
        foreach ($uptdData as $uptd) {
            $opsiuptd .= '<option value="' . $uptd->id . '">' . $uptd->nama . '</option>';
        }
        $getpola            = $this->Msetup->get_menu_tree();
        // $sisabagi = count($getpola) % 2;
        if (count($getpola) % 2 != 0) {
            $total = count($getpola);
            $half = ($total - 1) / 2; // Jumlah elemen pada setengah bagian
            $firstPart = array_slice($getpola, 0, $half + 1); // Bagian pertama termasuk elemen tambahan
            $secondPart = array_slice($getpola, $half + 1); // Bagian kedua
        }
        // var_dump($firstPart, $secondPart);
        // die;
        $side                = $this->menuBox($firstPart);
        $side2                = $this->menuBox($secondPart);


        $enum = ['adm', 'man', 'opr', 'pjb', 'kadis', 'uptd', 'mhs', 'pjk', 'bpk'];
        $form[] = '
			  <form action="' . site_url('usermanagement/userrole/simpanMenu') . '" method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                    ' . $this->Form->inputEnumOptions('role', 'Role', $enum) . '
                </div>
                <div class="row">
               <div class="col-md-6">' .
            $side
            . '</div>
              <div class="col-md-6">' .
            $side2
            . '</div>

            <div class="col-md-12 text-center">
                    <div class="btn-group">
                        <button class="btn btn-outline-danger mr-1" type="reset">
                            <i class="fa fa-undo"></i> Reset
                        </button>
                        <button class="btn btn-outline-primary" type="submit" name="AKSI" value="Save">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
                
        </form>
        <script>
         function searchRoles(role) {
            $.ajax({
                url: "' . site_url('usermanagement/UserRole/getMenu') . '", 
                type: "POST",
                data: { role: role },
                success: function(response) {
                   var html = "";
                    var checkedIds = [];

                    if (Array.isArray(response) && response.length > 0) {
                        $.each(response, function(index, item) {
                            if (item.idmenu) { 
                                html += "<li>" + item.name + " - " + item.idmenu + "</li>";
                                checkedIds.push(item.idmenu); 
                            }
                        });
                    } else {
                        html = "<p>Tidak ada hasil ditemukan.</p>";
                    }
                $("#results").html(html);

                    $("input[type=\"checkbox\"]").each(function() {
                        var checkboxId = $(this).attr("id").replace("check", "");
                        if (checkedIds.length > 0 && checkedIds.includes(checkboxId)) {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    });
                },
                error: function() {
                    $("#results").html("Terjadi kesalahan.");
                }
            });
        }

       
        var initialRole = $("#role").val(); 
        searchRoles(initialRole);

        $("#role").change(function() {
            var selectedRole = $(this).val();
             if (selectedRole) {
                var confirmation = confirm("Apakah Anda yakin akan mengganti role? Data Anda belum disimpan."); 
                if (confirmation) {
                    searchRoles(selectedRole);
                } else {
                    searchRoles(selectedRole);
                }
            }
        });
        </script>
        ';

        return $form;
    }


    public function get_menu_tree($parent_id = 0)
    {
        $this->db->where('parent_id', $parent_id);
        $this->db->order_by('id', 'ASC');
        $query     = $this->db->get('menu');
        $menu     = $query->result_array();
        $tree     = array();
        foreach ($menu as $menu) {
            $children = $this->get_menu_tree($menu['id']);
            if ($children) {
                $menu['children'] = $children;
            }
            $tree[] = $menu;
        }
        return $tree;
    }

    public function menuBox($menus, $level = 0)
    {
        $html = [];
        $indentation = str_repeat('&nbsp;', $level * 4); // Menentukan indentasi berdasarkan level

        foreach ($menus as $menu) {
            $html[] = '<div class="form-check" style="margin-left: ' . $level * 20 . 'px;">
                            <input class="form-check-input check-' . $menu["parent_id"] . '" type="checkbox" value="' . $menu["id"] . '" id="check' . $menu["id"] . '" data-id="' . $menu["id"] . '" name="menus[]">
                            <label class="form-check-label" for="check' . $menu["id"] . '" style="margin-top:-10px">
                                ' . $indentation . $menu["name"] . '
                            </label>
                       </div>
                       ';

            if (isset($menu['children'])) {
                $html[] = $this->menuBox($menu['children'], $level + 1); // Rekursi dengan level yang meningkat
            };
        }

        $html[] = '
                <script>
              
                function sendData(id, role, action){
                    $.ajax({
                        url: "' . site_url('usermanagement/UserRole/') . '"+action,
                        type: "POST",
                        data: {
                            id: id,
                            role: role,
                        },
                        seccess: function(response) {
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error saving data:", error);
                        }
                    }); 
                }

                function setUpCheckboxs(idMain = null, classMain = null,idSecondary1 = null, classSecondary1 = null,idSecondary2 = null, classSecondary2 = null) {
                  var role = $("#role").val();    
                if (idMain) {
                        $("#" + idMain).on("change", function() {
                            if ($(this).is(":checked")) {
                                if (classMain) {
                                    
                                    $("." + classMain).prop("checked", true);
                                }
                                if (classSecondary1) {
                                    $("." + classSecondary1).prop("checked", true);
                                }
                                if (classSecondary2) {
                                    $("." + classSecondary2).prop("checked", true);
                                }
                            } else {
                                if (classMain) {
                                    $("." + classMain).prop("checked", false);
                                }
                                if (classSecondary1) {
                                    $("." + classSecondary1).prop("checked", false);
                                }
                                if (classSecondary2) {
                                    $("." + classSecondary2).prop("checked", false);
                                }
                            }
                        });
                    }

                    if (classMain) {
                            $("." + classMain).on("change", function() {
                                if ($(this).is(":checked")) {
                                    if (idMain) {
                                        $("#" + idMain).prop("checked", true);
                                    }
                                } else {
                                    if ($("." + classMain + ":checked").length > 0) {
                                        if (idMain) {
                                            $("#" + idMain).prop("checked", true);
                                        }
                                    } else {
                                        if (idMain) {
                                            $("#" + idMain).prop("checked", false);
                                        }
                                    }
                                }
                            });
                    }

                    if (idSecondary1) {
                        $("#" + idSecondary1).on("change", function() {
                            if ($(this).is(":checked")) {
                                if (classSecondary1) {
                                    $("." + classSecondary1).prop("checked", true);
                                }
                            } else {
                                if (classSecondary1) {
                                    $("." + classSecondary1).prop("checked", false);
                                }
                            }
                        });
                    }

                    if (classSecondary1) {
                        $("." + classSecondary1).on("change", function() {
                            if ($(this).is(":checked")) {
                                if (idSecondary1) {
                                    $("#" + idSecondary1).prop("checked", true);
                                }
                                if (idMain) {
                                    $("#" + idMain).prop("checked", true);
                                }
                            } else {
                                if ($("." + classSecondary1 + ":checked").length > 0) {
                                    if (idSecondary1) {
                                        $("#" + idSecondary1).prop("checked", true);
                                    }
                                } else {
                                    if (idSecondary1) {
                                        $("#" + idSecondary1).prop("checked", false);
                                    }
                                }
                            }
                        });
                    }

                    if (idSecondary2) {
                        $("#" + idSecondary2).on("change", function() {
                            if ($(this).is(":checked")) {
                                if (classSecondary2) {
                                    $("." + classSecondary2).prop("checked", true);
                                }
                            } else {
                                if (classSecondary2) {
                                    $("." + classSecondary2).prop("checked", false);
                                }
                            }
                        });
                    }

                    if (classSecondary2) {
                            $("." + classSecondary2).on("change", function() {
                                if ($(this).is(":checked")) {
                                    if (idSecondary2) {
                                        $("#" + idSecondary2).prop("checked", true);
                                    }
                                    if (idMain) {
                                        $("#" + idMain).prop("checked", true);
                                    }
                                } else {
                                    if ($("." + classSecondary2 + ":checked").length > 0) {
                                        if (idSecondary2) {
                                            $("#" + idSecondary2).prop("checked", true);
                                        }
                                    } else {
                                        if (idSecondary2) {
                                            $("#" + idSecondary2).prop("checked", false);
                                        }
                                    }
                                }
                            });
                    }
                }

            

            setUpCheckboxs("check2", "check-2", "check24", "check-24");
            setUpCheckboxs("check3", "check-3", "check29", "check-29","check40", "check-40");
            setUpCheckboxs("check4", "check-4");
            setUpCheckboxs("check6", "check-6", "check7", "check-7","check8", "check-8");
            setUpCheckboxs("check9", "check-9");
            setUpCheckboxs("check10", "check-10");
            setUpCheckboxs("check54", "check-54");
            setUpCheckboxs("check90", "check-90");

        

            </script>

        ';
        return implode('', $html);
    }
}
