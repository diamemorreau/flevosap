<?php
/**
 * Created by: Jake 2020
 */

class UserModel extends BaseModel
{
    private int $id, $type;
    private string $username, $password;
    private $createdAt, $updatedAt;

    public function __construct()
    {
        parent::__construct();
    }

    public function findByEmail(string $email) : void
    {
        $query = "SELECT * FROM gebruikers WHERE gebruikersnaam = :username";
        if ($stmt = $this->pdo->prepare($query)) :
            $stmt->bindParam(':username', $email, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch();
            if($data !== false) :
                $this->id = $data['id'];
                $this->username = $data['gebruikersnaam'];
                $this->password = $data['wachtwoord'];
                $this->createdAt = $data['created_at'];
                $this->updatedAt = $data['updated_at'];
            endif;
        endif;
    }

    public function fetchById(int $id)
    {
        $query = "SELECT * FROM gebruikers WHERE id = :id";
        if ($stmt = $this->pdo->prepare($query)) :
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch();
            if($data !== false) {
                $this->id = $data['id'];
                $this->username = $data['gebruikersnaam'];
                $this->password = $data['wachtwoord'];
                $this->type = $data['type'];
                $this->createdAt = $data['created_at'];
                $this->updatedAt = $data['updated_at'];
                return $this;
            }
        endif;
    }

    public function checkExistingUsername(string $username) : bool
    {
        $query = "SELECT * FROM gebruikers WHERE gebruikersnaam = :username";
        if ($stmt = $this->pdo->prepare($query)) :
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() == 0;
        endif;
    }

    public function find($id, $type)
    {
        $query = "SELECT * FROM gebruikers WHERE id = :id and `type`=:type";
        if ($stmt = $this->pdo->prepare($query)) :
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':type', $type, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch();
            if($data !== false) :
                $this->id = $data['id'];
                $this->username = $data['gebruikersnaam'];
                $this->password = $data['wachtwoord'];
                $this->type = $data['type'];
                $this->createdAt = $data['created_at'];
                $this->updatedAt = $data['updated_at'];
            endif;
        endif;
    }

    public function profile($id)
    {
        if ($id != null) {
            $stmt = $this->pdo->prepare('SELECT * FROM gebruikers WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        }
    }

     public function findByName($username,$type)
    {
        $query = "SELECT * FROM gebruikers WHERE gebruikersnaam = :username and `type`=:user_type";
        if ($stmt = $this->pdo->prepare($query)) {
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':user_type', $type, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch();
            if($data !== false) :
                $this->id = $data['id'];
                $this->username = $data['gebruikersnaam'];
                $this->password = $data['wachtwoord'];
                $this->createdAt = $data['created_at'];
                $this->updatedAt = $data['updated_at'];
                return 1;
            endif;
            } else{
                return 0;
            }
    }

    public function store(UserModel $user)
    {
        $query = "INSERT INTO gebruikers (gebruikersnaam, wachtwoord, `type`) VALUES (:username, :password, :type)";
        if ($stmt = $this->pdo->prepare($query)) :
            $stmt->bindValue(':username', $user->getUserName());
            $stmt->bindValue(':type', $user->getType());
            $stmt->bindValue(':password', password_hash($user->getPassword(),PASSWORD_DEFAULT));
            return $stmt->execute();
        endif;
        return false;
    }

    public function updateUser(UserModel $user){
        $query = "UPDATE gebruikers SET 
                    gebruikersnaam = :gebruikersnaam, 
                    wachtwoord = :wachtwoord 
                    WHERE id = :id";
        if ($stmt = $this->pdo->prepare($query)) :
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':gebruikersnaam', $user->getUserName());
            $stmt->bindValue(':wachtwoord', password_hash($user->getPassword(),PASSWORD_DEFAULT));
            return $stmt->execute();
        endif;
        return false;
    }

    public function delete($id)
    {
        if ($id != null) {
            $stmt = $this->pdo->prepare('SELECT * FROM gebruikers WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                return "0";
            }
            $stmt = $this->pdo->prepare('DELETE FROM gebruikers WHERE id = ?');
            $stmt->execute([$id]);
            return "1";
        } else {
            return "0";
        }
    }

    public function all()
    {
        $query = 'SELECT * FROM gebruikers';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = array();
        while($data = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $user = new UserModel();
            $user->load($data);
            $result[]=$user;
        }
        return $result;
    }

    private function load($data)
    {
        $this->setId($data['id']);
        $this->setUsername($data['gebruikersnaam']);
        $this->setPassword($data['wachtwoord']);
        $this->setType($data['type']);
        $this->setCreatedAt($data['created_at']);
        $this->setUpdatedAt($data['updated_at']);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return UserModel
     */
    public function setType(int $type): UserModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUserName(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }



}