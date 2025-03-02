### Broadcasting Channels Information:


### 1. `user.{id}`  

EventName : `App\Events\UserEvent`

Its managing :

- New User to User Conversation 
- Block Unblock User
- Added to group
- Private message read by user

### 2. `group.{id}`

EventName : `App\Events\GroupEvent`

Its managing :

- Group Details Updated;
- Member role in group updated
- New Member added in group
- Group Deleted By group Owner
- Group message read by all group members
- New group message arrived
- Group message typing status

### 3. `updates.{id}`

EventName : `App\Events\UpdatesEvent`

Its managing: 

- Updates to all users about new updated details

### 4. `user-status`

EventType: Presence channel

Its managing : user online offline updates

### 5. `chat`

Its whispering for 2 channels :
- `start-typing.{id}`
- `stop-typing.{id}`


