<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/scaffold-interface-css/main.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>The scaffold interface</title>
    </head>
    <body>
        <div id = "el1" class = 'container'>
            <h2 class = "thin">The <i>Scaffold Interface</i> for laravel</h2>
            <div style = 'margin-top: 2cm;'></div>
            <button v-if = '!show' transition = "fade" class = 'btn animated' @click = 'show = ! show'><i class = 'material-icons left'>create</i>New Table</button>
            <br>
            <div class="row">
                <div transition = "fade" class="col s5 animated" v-if = 'show'>
                    <p transition = "fade" class = 'animated red-text flow-text' v-if = 'error'>@{{errorMsg}}</p>
                    <form id = 'form' method = 'post' action = '{{URL::to("/")}}/scaffold/guipost/'>
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <table class = 'ta'>
                            <tr>
                                <td>
                                    <div class = 'input-field'>
                                        <input id = 'TableName' name='TableName' required='' aria-required='true' type='text'>
                                        <label for='TableName'>TableName</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="template" type="radio" value = "materialize" checked id="materialize" />
                                    <label for="materialize">Materialize</label>
                                </td>
                                <td>
                                    <input name="template" type="radio" value = "bootstrap" id="bootstrap" />
                                    <label for="bootstrap">Bootstrap</label>
                                </td>
                            </tr>
                            <tr transition = "fade" class = 'animated' v-for = "el in rows">
                                <td>
                                    <div class="input-field col s12">
                                        <select class = 'browser-default' id = "opt@{{el}}" name = "opt@{{el}}" data-id = "@{{el}}">
                                            <option v-for = "element in select" value = "@{{element}}">@{{element}}</option>
                                            <label for = "opt@{{el}}">Select Type</label>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class = 'input-field'>
                                        <input id = 'atr@{{el}}' name = 'atr@{{el}}' required='' aria-required='true' type='text'>
                                        <label for = 'atr@{{el}}'>Attribute</label>
                                    </div>
                                </td>
                            </tr>
                            <!-- One To Many Relationship-->
                            <tr transition = "fade" class = 'animated' v-for = "final in OneToManyData">
                                <td>
                                    <input  name = "tbl@{{final.id}}" type = "hidden" required='' aria-required='true' value = "@{{final.table}}">
                                    <p class = 'flow-text'>@{{final.table}}</p>
                                </td>
                                <td>
                                    <input  name = "on@{{final.id}}" type = "hidden" required='' aria-required='true' value = "@{{final.onData}}">
                                    <p class = 'flow-text'>@{{final.onData}}</p>
                                </td>
                                <td>
                                    <a class = 'btn-floating red' @click = 'removeRelation(final)'><i class = 'material-icons left'>delete</i></a>
                                </td>
                            </tr>
                            <tr transition = "fade" class = 'animated' v-if = "OneToManyBool">
                                <td>
                                    <select @change = 'getAttr($index)' class = 'browser-default' id = "tbl" name = "tbl@{{el}}" data-id = "@{{el}}">
                                        <option value="scfld#01" disabled selected>Choose your table</option>
                                        <option v-for = "element in OneToMany" value = "@{{element}}">@{{element}}</option>
                                    </select>
                                </td>
                                <td>
                                    <select class = 'browser-default' id = "on" name = "on" data-id = "on">
                                        <option value="scfld#01" disabled selected>Choose your option</option>
                                        <option v-for = "elementt in attributes" value = "@{{ elementt }}">@{{* elementt }}</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div transition = 'fade' class = "row animated" v-if = 'submit'>
                            <button type = 'submit' class = 'val btn green col s12 animated'>
                            <i class = 'material-icons left'>done</i>
                            Done
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col s7">
                    @if (Session::has('status'))
                    <div transition = 'fade' @click = 'closeMsg' class="msg card-panel #fce4ec green lighten-5 animated">
                        <div class = 'row'>
                            <div class = 'col s5'><i class = 'large material-icons'>info</i></div>
                            <div class = 'col s7'>
                                <blockquote>
                                    {{ Session::get('status') }}
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div v-if = 'show'  transition = "actions" class = 'animated'>
                        <div class='card-panel #fafafa grey lighten-5'>
                            <h4 class = 'center thin'>Rows</h4>
                            <div class = 'row center actionRow' >
                                <div transition = "actions" class = "animated" v-if = '!more'>
                                    <a class = 'btn blue'  @click = "increment"><i class = 'material-icons left'>add</i>new</a>
                                    <a class = 'btn red'   @click = 'decrement'><i class = 'material-icons left'>delete</i>remove</a>
                                    <a class = 'btn green' @click = 'more = true'><i class = 'material-icons left'>arrow_forward</i>more</a>
                                </div>
                                <div v-show = 'more' transition = "actions" class = "animated">
                                    <a class = 'btn purple' @click = 'lastStep'><i class = 'material-icons left'>arrow_back</i>back</a>
                                    <a v-if = '!submit' @click = 'addOneToMany' class = 'btn #0d47a1 blue darken-4'><i class = 'material-icons left'>device_hub</i>One To Many</a>
                                    <a v-if = '!submit' @click = 'lastOne' class = 'btn orange'><i class = 'material-icons left'>layers</i>ready</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Interface Core-->
                    <div>
                        <table class = 'centered highlight'>
                            <thead>
                                <th>Name</th>
                                <th>Created at</th>
                                <th>State</th>
                                <th>Link</th>
                                <th>Rollback</th>
                            </thead>
                            <tbody>
                                @foreach($scaffold as $value)
                                <tr>
                                    <td>{{$value->tablename}}</td>
                                    <td>{{$value->created_at}}</td>
                                    <td><span class = "scaffoldv {{$toto = Schema::hasTable($value->tablename) ? 'green' : 'red'}} white-text">{{$toto = Schema::hasTable($value->tablename) ? 'Migrated' : 'Not migrated'}}</span></td>
                                    <td><a href="{{URL::to('/')}}/{{lcfirst(str_singular($value->tablename))}}" class = 'btn-floating blue white-text'><i class = 'material-icons'>send</i></a></td>
                                    <td><a href = '#modal1' class = 'delete btn-floating pink modal-trigger' data-link = '/scaffold/guidelete/{{$value->id}}/'><i class = 'material-icons'>repeat</i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $scaffold->render() !!}
                        <div class="pushDown"></div>
                        <span>Scaffold-interface <span class = 'scaffoldv orange white-text'>dev-master</span></span>
                        <p class = 'light'>Copyright (c) {{date('Y')}} Amrani Houssian<br><br>
                            Permission is hereby granted, free of charge, to any person obtaining a copy
                            of this software and associated documentation files (the "Scaffold-Interface"), to deal
                            in the Software without restriction, including without limitation the rights
                            to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
                            copies of the Software, and to permit persons to whom the Software is
                            furnished to do so, subject to the following conditions:<br><br>
                            The above copyright notice and this permission notice shall be included in
                            all copies or substantial portions of the Software.<br><br>
                            THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
                            IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
                            FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
                            AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
                            LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
                            OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
                            THE SOFTWARE.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed-action-btn horizontal click-to-toggle" style="bottom: 45px; right: 24px;">
            <a class="btn-floating btn-large red">
                <i class="large mdi-navigation-menu"></i>
            </a>
            <ul>
                <li><a href = "{{URL::to('/')}}/scaffold/scaffoldHomePage"class="btn-floating blue"><i class="material-icons">view_list</i></a></li>
                <li><a href = "{{URL::to('/')}}/scaffold/scaffoldHomePageDelete"class="btn-floating red darken-1"><i class="material-icons">delete</i></a></li>
                <li><a href = "{{URL::to('/')}}/scaffold/scaffoldHomePageIndex" class="btn-floating #7cb342 light-green darken-1"><i class="material-icons">send</i></a></li>
                <li><a href = "{{URL::to('/')}}/scaffold/rollback" class="btn-floating pink"><i class="material-icons">repeat</i></a></li>
                <li><a href = "{{URL::to('/')}}/scaffold/migrate" class="btn-floating orange"><i class="material-icons">input</i></a></li>
            </ul>
        </div>
        <div id="modal1" class="modal">
            <div class = "row AjaxisModal">
            </div>
        </div>
    </body>
    <script type="text/javascript">
    var baseURL = "{{URL::to('/')}}";
    var scaffoldList = {!! $scaffoldList !!};
    </script>
    <script type="text/javascript" src = "https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
    <script type="text/javascript" src = "http://cdn.jsdelivr.net/vue/1.0.17/vue.js"></script>
    <script type="text/javascript" src = "/js/AjaxisMaterialize.js"></script>
    <script type="text/javascript" src = "/js/scaffold-interface-js/main.js"></script>
</html>
