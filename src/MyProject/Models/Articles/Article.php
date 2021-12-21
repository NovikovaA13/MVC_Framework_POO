<?php
namespace MyProject\Models\Articles;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;
use MyProject\Exceptions\InvalidArgumentException;

class Article extends ActiveRecordEntity
{
    /**
     * @var int
     */
    protected $authorId;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $text;
    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'articles';
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        $author = User::getOneById($this->authorId);
        return $author;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param User $user
     */
    public function setAuthor(User $user): void
    {
        $this->authorId = $user->getId();
    }

    /**
     * @param array $articleData
     * @param User $user
     * @return Article
     * @throws InvalidArgumentException
     */
    public static function createPostFromArray(array $articleData, User $user)
    {
        if(empty($articleData['title'])){
           throw new InvalidArgumentException('Le title est vide.');
        }
        if(empty($articleData['text'])){
            throw new InvalidArgumentException('Le text est vide.');
        }
        $article = new Article();
        $article->title = $articleData['title'];
        $article->text = $articleData['text'];
        $article->setAuthor($user);
        $article->save();
        return $article;
    }
    public function updatePostFromArray(array $articleData): Article
    {
        if(empty($articleData['title'])){
            throw new InvalidArgumentException('Le title est vide.');
        }
        if(empty($articleData['text'])){
            throw new InvalidArgumentException('Le text est vide.');
        }
        $this->setTitle($articleData['title']);
        $this->setText($articleData['text']);
        $this->save();
        return $this;

    }
}
