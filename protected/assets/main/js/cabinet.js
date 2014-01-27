$(function(){
    $('.popup_close').on('click', function(){
        $(this).parents('.popup').hide(300);
    });

    $('#create_game').on('click', function(){
        $('.cabinet_game_creation').show(300);
    });

    $('#action_game_create').on('click', function(){
        error = 0;
        game_title = $('input[name=new_game_title]').val();
        if(!game_title || game_title == ''){
            $('input[name=new_game_title]').css({'outline': '2px solid red'});
            error = 1;
        }
        else{
            $('input[name=new_game_title]').css({'outline': 'none'});
        }
        module_id = $('#new_game_module option:checked').val();
        if(!module_id){
            $('.cabinet_game_modules').css({'outline': '2px solid red'});
            error = 1;
        }
        else{
            $('.cabinet_game_modules').css({'outline': 'none'});
        }
        if(!error){
            $.ajax({
                type: "POST",
                async: false,
                url: url_root + "cabinet/createGame",
                dataType: 'json',
                data: { 'game_title': game_title,
                        'module_id': module_id},
                success: function(data){
                    if(data.result == true){
                        location.reload();
                    }
                    else{
                        $('#game_creation_result').text('Создать игру не удалось, попробуйте позднее');
                    }
                }
            });
        }
    });

    $('.game_select').on('click', function(){
        game_id = $(this).attr('data-id');
        loadGame(game_id);
    });

    $(document).on('click', '.make_claim', function(){
        game_id = $(this).parents('#b_game_info').attr('data-id');
        $.ajax({
            type: "POST",
            async: false,
            url: url_root + "cabinet/makeClaim",
            data: {'game_id': game_id},
            success: function(data){
                $('#open_list').children('[data-id='+game_id+']').detach().clone().appendTo('#claimed_list');
                loadGame(game_id);
            }
        });
    });

    $(document).on('click', '.claim_accept', function(){
        game_id = $(this).parents('#b_game_info').attr('data-id');
        $.ajax({
            type: "POST",
            async: false,
            url: url_root + "cabinet/acceptClaim",
            data: {'game_id': game_id, 'user_id': $(this).parents('div.point_row').attr('data-id')},
            success: function(data){
                loadGame(game_id);
            }
        });
    });

    $(document).on('click', '.start_game', function(){
        game_id = $(this).parents('#b_game_info').attr('data-id');
        $.ajax({
            type: "POST",
            async: false,
            url: url_root + "cabinet/startGame",
            data: {'game_id': game_id},
            success: function(data){
                loadGame(game_id);
            }
        });
    });
});

function loadGame(game_id){
        $.ajax({
            type: "POST",
            async: false,
            url: url_root + "cabinet/getGameInfo",
            data: {'game_id': game_id},
            success: function(data){
                $('#cabinet_game_info').html(data);
            }
        });
}