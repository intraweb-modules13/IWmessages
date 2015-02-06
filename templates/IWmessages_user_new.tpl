<div id="IWmainContent">
    <script language="javascript">
        function send(){
            var error=false;
            document.newmsg.action="index.php?module=IWmessages&type=user&func=submit";
            if(document.newmsg.to_user.value=="" && document.newmsg.multi.value==0){
                alert("{{gt text="Not user especified."}}");//{{gt text="Not user especified."}}
                var error=true;
            }
            if(document.newmsg.subject.value=="" && !error){
                alert("{{gt text="No subject"}}");//{{gt text="No subject"}}
                var error=true;
            }
            for(i=1;i<4;i++){
                if(eval("document.newmsg.file"+i).value!="" && "{{$extensions}}".indexOf(eval("document.newmsg.file"+i).value.substring(eval("document.newmsg.file"+i).value.length-3,eval("document.newmsg.file"+i).value.length))==-1){
                    alert("{{gt text="The extension of the attached file"}} "+eval("document.newmsg.file"+i).value+" {{gt text="is not allowed. The allowed extensions are: "}}{{$extensions}}");//{{gt text="The extension of the attached file"}}{{gt text="is not allowed. The allowed extensions are: "}}
                    var error=true;
                }
            }
            if(!error){resposta=confirm("{{gt text="Confirm before send?"}}");}//{{gt text="Confirm before send?"}}
            if(!error && resposta){document.newmsg.submit();}
        }
        function notsend(){
            document.newmsg.action="index.php?module=IWmessages&type=user&func=view";
            document.newmsg.submit();
        }
    </script>

    {ajaxheader modname=IWmessages filename=IWmessages.js}
    {checkpermission component='IWmessages::' instance='::' level='ACCESS_ADMIN' assign='authadmin'}
    {include file=IWmessages_user_menu.tpl read=0}
    <h2>{gt text="Send private message"}</h2>
    <form class="z-form" enctype="multipart/form-data"  name="newmsg" method="post">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="replied" value="{$replied}" />
        <div class="z-formrow">
            <label for="to_user">{gt text="To"}:</label>
            <div class="z-formnote">
                {if isset($reply) && $reply neq ''}
                <input type="hidden" name="to_user" value="{$fromuser}" />{$fromuser}
                {else}
                {if isset($toUserFixed) && $toUserFixed}
                <input type="hidden" name="to_user" value="{$touser}" />{$touser}
                {else}

                <input id="to_user" type="text" autocomplete="off" name="to_user" size="23" maxlength="100" value="{$touser}" {if not $dissableSuggest}onKeyUp="autocompleteUser(this.value)"{/if}/>
                       {/if}
                       <div style="visibility: hidden; border: 1px solid #ffff9f; clear:both; position:absolute; margin-top: 20px; background-color:#ffffcf; padding: 5px; z-index: 1;" id="autocompletediv"></div>
                {/if}
                {if $canMulti && $reply eq '' && !$toUserFixed}
                <div style="width: 400px; float:right">
                    <div style="width: 200px; float:left">
                        <label for="to_user">{gt text="and/or to the group"}:</label>
                    </div>
                    <div style="width: 200px; float:left">
                        <select name="multi" onClick="hideAutoCompete()">
                            <option value="0">{gt text="Choose a group..."}</option>
                            {section name=groupsMulti loop=$groupsMulti}
                            <option {if $groupsMulti[groupsMulti].id eq $to_group}selected{/if} value="{$groupsMulti[groupsMulti].id}">{$groupsMulti[groupsMulti].name}</option>
                            {/section}
                        </select>
                    </div>
                </div>
                {else}
                <input type="hidden" name="multi" value="" />
                {/if}
            </div>
        </div>
        <div class="z-formrow">
            <label for="to_user">{gt text="Subject"}:</label>
            <input type="text" onClick="hideAutoCompete()" name="subject" value="{$subject}" size="70" maxlength="100" />
        </div>
        {if $icons neq ''}
        <div class="z-formrow">
            <label for="none">{gt text="Icon"}:</label>
            <div class="z-formnote">
                <input type="radio" name="image" value="" checked="checked" />
                {section name=icons loop=$icons}
                {if $icons[icons].imgsrc neq ''}
                <input type="radio" name="image" value="{$icons[icons].imgsrc}" {if $icons[icons].imgsrc eq $image}checked{/if} /><img src="modules/IWmain/images/smilies/{$icons[icons].imgsrc}" alt="" />
                {/if}
                {/section}
            </div>
        </div>
        {else}
        <input type="hidden" name="image" value="" />
        {if $authadmin}
        <div class="z-informationmsg z-formnote">
            {gt text="You can activate smilies from module configuration."}
        </div>
        {/if}
        {/if}
        <div class="z-formrow">
            <label for="to_user">{gt text="Message"}:</label>
            <textarea name="message" rows="10" cols="100%" id="intraweb">{$message}</textarea>
        </div>
 <!--       <div class="z-formnote">
            {messages_allowedhtml}
        </div>
-->
        {if $canUpdate}
        <fieldset>
            <legend>{gt text="Attached files"}</legend>
            <div class="z-formrow">
                {gt text="Allowed extensions"}: ({$extensions})
            </div>
            <div class="z-formrow">
                <label for="file1">{gt text="File 1"}:</label>
                <input type="file" name="file1" size="50" maxlength="150" />
            </div>
            <div class="z-formrow">
                <label for="file2">{gt text="File 2"}:</label>
                <input type="file" name="file2" size="50" maxlength="150" />
            </div>
            <div class="z-formrow">
                <label for="file3">{gt text="File 3"}:</label>
                <input type="file" name="file3" size="50" maxlength="150" />
            </div>
        </fieldset>
        {else}
        <input type="hidden" name="file1" value="" />
        <input type="hidden" name="file2" value="" />
        <input type="hidden" name="file3" value="" />
        {/if}
        <input type="hidden" name="reply" value="{if isset($reply1)}{$reply1}{/if}" />
        <div class="z-center">
            <input type="hidden" name="msg_id" value="{$msgid}" />
            <span class="z-buttons">
                <a href="javascript:javascript:send();">
                    {gt text="Submit"}
                </a>
            </span>
            <span class="z-buttons">
                {if isset($reply) AND $reply neq ""}
                <a href="javascript:notsend();">
                    {gt text="Cancel reply"}
                </a>
                {else}
                <a href="javascript:notsend();">
                    {gt text="Cancel sending"}
                </a>
                {/if}
            </span>

        </div>
        {if isset($reply) AND $reply neq ''}
        <fieldset>
            <legend>{gt text="Original message"}</legend>
            <div>{$reply|safehtml}</div>
        </fieldset>
        {/if}
    </form>
</div>
{notifydisplayhooks eventname='iwmessages.ui_hooks.iwmessages.form_edit'}
