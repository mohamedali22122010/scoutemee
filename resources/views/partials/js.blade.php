<script type="text/javascript">
	$(document).ready(function() {
		//console.log("{{\Request::route()->getName()}}")
		/*if ($("form.searchForm").length) {
			var addressPicker = new AddressPicker({
				autocompleteService : {
					types : ['(cities)'],
					componentRestrictions : {
						country : 'US'
					}
				}
			});
			$('#location').typeahead(null, {
				displayKey : 'description',
				source : addressPicker.ttAdapter()
			});
			addressPicker.bindDefaultTypeaheadEvent($('#location'))
			$(addressPicker).on('addresspicker:selected', function(event, result) {
				$("#location").val(result.address());
				$("#latitude").val(result.lat());
				$("#longitude").val(result.lng());
			});
		}*/
		//Search Form auto complete Cities (Using Typeahead and typeahead-addresspicker)
        $(document).on('click', '.followBox-link', function (e) {
		    $element = $(this);
			e.preventDefault();
			var profile_id = $(this).attr('rel');
			var url = $(this).attr('href');
			if (profile_id) {
				//ajax post the form
				$.ajax({
					url : url,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
    			     },
					type : "post",
					success : function(res) {
					    if(res.status){
						  $(".countfollowers p").text(res.followers);
						  $(".followBox-link").show()
						  $element.hide();
						}
					}
				})
			} else {
				alert("Please give a title to task");
			}
		});
		
		$("#post_review").submit(function(e){
		    e.preventDefault();
		    var data = new FormData(document.querySelector('#post_review'));
		    $.ajax({
                type: "POST",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
                },
                url: "{{ URL::action('ProfileController@postReviewAjax')}}",
                data: data,
                processData: false,
                contentType: false,
                success: function (data) {
                    $("#text_area_review").val("");
                    $(".mfp-close").trigger("click");
                },
                error: function (data) {
                    
                }
            });
		});
        
        $(document).on('click', '.loadMorePost', function (e) {
           e.preventDefault();
           element = $(this);
           $.ajax({
                url : element.attr("rel"),
                dataType: 'json',
                beforeSend: function (xhr) {
                    element.html('<i class="icon icon-spin">&#xf0e2;</i><span>Loading ...</span>');
                },
            }).done(function (data) {
                $('.user-posts').append(data);
                element.parent().remove();
            }).fail(function () {
                element.parent().remove();
            });
        });
        
        $(document).on('click', '.loadMoreVideos', function (e) {
           e.preventDefault();
           element = $(this);
           $.ajax({
                url : element.attr("rel"),
                dataType: 'json',
                beforeSend: function (xhr) {
                    element.html('<i class="icon icon-spin">&#xf0e2;</i><span>Loading ...</span>');
                },
            }).done(function (data) {
                $('#videoList-ul').append(data);
                element.parent().remove();
                callPopUpPlayer();
            }).fail(function () {
                callPopUpPlayer();
                element.parent().remove();
            });
        });
        
        $(document).on('click', '.loadMoreAudios', function (e) {
           e.preventDefault();
           element = $(this);
           $.ajax({
                url : element.attr("rel"),
                dataType: 'json',
                beforeSend: function (xhr) {
                    element.html('<i class="icon icon-spin">&#xf0e2;</i><span>Loading ...</span>');
                },
            }).done(function (data) {
                $('#musicList-ul').append(data);
                element.parent().remove();
                callPopUpPlayer();
            }).fail(function () {
                callPopUpPlayer();
                element.parent().remove();
            });
        });
        
        $(document).on('click', '.loadMoreImages', function (e) {
           e.preventDefault();
           element = $(this);
           $.ajax({
                url : element.attr("rel"),
                dataType: 'json',
                beforeSend: function (xhr) {
                    element.html('<i class="icon icon-spin">&#xf0e2;</i><span>Loading ...</span>');
                },
            }).done(function (data) {
                $('#imageList-ul').append(data);
                element.parent().remove();
                callPopUpPlayer();
            }).fail(function () {
                callPopUpPlayer();
                element.parent().remove();
            });
        });
        
        $(document).on('click', '.loadMoreProfilesAdvancedSearch', function (e) {
            e.preventDefault();
            element = $(this);
            currentPage = $(".currentPage").val();
            $(".currentPage").val(parseInt(currentPage)+1);
            if($("#advanced-search").length){
            var data = new FormData(document.querySelector('#search-profiles-advanced'));
            }else{
                var data = new FormData(document.querySelector('#search-profiles'));
            }
            console.log(data)
            console.log($("#advanced-search").length)
            $.ajax({
                type: "POST",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
                    element.html('<i class="icon icon-spin">&#xf0e2;</i><span>Loading ...</span>');
                },
                url: "{{ URL::action('HomeController@advancedSearch')}}",
                data: data,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('.usersProfiles').append(data.view);
                    element.html('<i class="icon">&#xf0e2;</i><span>Load More</span>');
                    if(data.counts < 9)
                        $(".loadMoreProfilesAdvancedSearch").remove()
                    
                },
                error: function (data) {
                    
                }
            });
        });
        
        $(document).on('click', '.deletePost', function (e) {
            e.preventDefault();
            post_id = $(this).attr('rel');
            element = $(this);
            if (confirm("Are You Sure You Want To Delete") == true) {
                $.ajax({
                    type: "POST",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
                    },
                    url: "{{ URL::action('ProfileController@deletePostAjax')}}",
                    data: {"post_id":post_id},
                    success: function (data) {
                        if(data.status)
                            location.reload();
                    },
                    error: function (data) {
                        
                    }
                });
            }
        });
        $(document).on('click', '.LikeUnLikePost', function (e) {
            $element = $(this);
            e.preventDefault();
            var post_id = $(this).attr('rel');
            $.ajax({
                type: "POST",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
                },
                url: "{{ URL::action('ProfileController@likePostAjax')}}",
                data: {"post_id":post_id},
                success: function (data) {
                    if(data.status){
                        if(data.type == 'liked'){
                            $element.addClass("active")
                            $counter = $element.siblings('.count').text();
                            $element.siblings('.count').html(parseInt($counter)+1);
                        }if(data.type == 'unliked'){
                            $element.removeClass("active")
                            $counter = $element.siblings('.count').text();
                            $element.siblings('.count').html(parseInt($counter)-1);
                        }
                        
                    }
                },
                error: function (data) {
                    
                }
            });
        });
        $(document).on('change', '#userRating', function (e) {
            e.preventDefault();
            element = $(this);
            if(element.hasClass("self")){
                return;
            }
            auth = element.attr("attr-auth");
            profile_id = element.attr("rel");
            rate_value = element.val();
            if(auth == 0)
            {
                
            }else{
                $.ajax({
                    type: "POST",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
                    },
                    url: "{{ URL::action('ProfileController@rateProfileAjax')}}",
                    data: {"profile_id":profile_id,"rate_value":rate_value},
                    success: function (data) {
                        
                    },
                    error: function (data) {
                        
                    }
                });
            }
        })
        $(document).on('change', '#search-sort-options', function (e) {
            e.preventDefault();
            $("#sort-option").val($(this).val());
            $("#sort-option-advanced").val($(this).val());
            if($("#advanced-search").length){
                $("#search-profiles-advanced").submit();
            }else{
                $("#search-profiles").submit();
            }
        });
        $(document).on('click','#create_message', function (e) {
            // Gets a reference to the form element, assuming
            // it contains the id attribute "signup-form".
            var form = document.getElementById('create_message');
            
            // Adds a listener for the "submit" event.
            form.addEventListener('submit', function(event) {
            
              // Prevents the browser from submiting the form
              // and thus unloading the current page.
              event.preventDefault();
            
              // Sends the event to Google Analytics and
              // resubmits the form once the hit is done.
              ga('send', 'event', 'Create Message', 'submit', {
                hitCallback: function() {
                  //form.submit();
                }
              });
            });
        });
        $(document).on('click','#accept-message', function (e) {
            e.preventDefault();
            //$("#message-actions").html('<i class="icon icon-spin">&#xf0e2;</i><span>Sending Acceptance ...</span>');
            $("#message-reply-content").val("Accepted the offer");
            $("#reply_message").submit();
        });
        $(document).on('click','#decline-message', function (e) {
            e.preventDefault();
            //$("#message-actions").html('<i class="icon icon-spin">&#xf0e2;</i><span>Sending Decline ...</span>');
            $("#message-reply-content").val("Declined the offer");
            $("#reply_message").submit();
        });
        
        $('#search-profiles').submit(function(e){
            $(".currentPage").val(1);
        });
        $(document).on('click','#search-profiles-advanced', function (e) {
            $(".currentPage").val(1);
            // Gets a reference to the form element, assuming
            // it contains the id attribute "signup-form".
            var form = document.getElementById('search-profiles-advanced');
            
            // Adds a listener for the "submit" event.
            form.addEventListener('submit', function(event) {
            
              // Prevents the browser from submiting the form
              // and thus unloading the current page.
              event.preventDefault();
            
              // Sends the event to Google Analytics and
              // resubmits the form once the hit is done.
              ga('send', 'event', 'Advanced Search', 'submit', {
                hitCallback: function() {
                  form.submit();
                }
              });
            });
        });
        
        
           
	}); 
	
	$(function(){
        var bands = new Bloodhound({
           datumTokenizer: Bloodhound.tokenizers.whitespace,
           queryTokenizer: Bloodhound.tokenizers.whitespace,
           //datumTokenizer: function(d) { return d },
           prefetch: {
              url: '{{asset("assets/js/bands.json")}}'+'?nocache='+ (new Date()).getTime(),
           }
       });

       bands.initialize();

       $('#bands').typeahead(null,{
           displayKey: function(s) { return s },
           name: 'bands',
           source: bands.ttAdapter()
       });
    });
	
	function callPopUpPlayer() {
       if ($("div.musicWrapper, div.videosWrapper, div.photosWrapper").length) {

           $('.openAjax').magnificPopup({
               type: 'ajax',

               // When elemened is focused, some mobile browsers in some cases zoom in
               // It looks not nice, so we disable it:
               callbacks: {
                   beforeOpen: function() {

                       if ($(window).width() < 700) {
                           this.st.focus = false;
                       } else {
                           this.st.focus = '#name';
                       }
                   },

                   ajaxContentAdded: function() {
                       if ($(".customVideoPlayer").length) {

                           var player = videojs(document.getElementsByClassName('customVideoPlayer')[0], {
                                   /* Options */
                               },
                               function() {
                                   //Once Video.js has initialized it will call this function.
                               });
                       }
                       //Video Player

                       if ($(".customAudioPlayer").length) {

                           var player = videojs(document.getElementsByClassName('customAudioPlayer')[0], {
                                   /* Options */
                               },
                               function() {
                                   //Once Video.js has initialized it will call this function.
                               });
                       }
                       //Audio Player
                   }
                   //initialize "videoJS" after ajax content is loaded and added to the dom
               },

               midClick: true // allow opening popup on middle mouse click. Always set
           });
       }
   }
</script>